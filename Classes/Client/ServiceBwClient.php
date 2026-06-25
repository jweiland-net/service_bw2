<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use JWeiland\ServiceBw2\Client\Request\RequestInterface;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

/**
 * Client to be used for all Service BW API v2 requests
 */
readonly class ServiceBwClient
{
    /**
     * Max records to retrieve from Service BW API.
     *
     * Currently, Service BW API PageBrowser is broken and returns
     * always itemPages=1 instead of the real number of pages.
     * Set this value back to ~100 if their responses are repaired again.
     */
    protected const MAX_ITEMS_EACH_REQUEST = 1000;

    protected const MAX_REQUEST_RETRIES = 2;

    /**
     * I got a lot of connect timeouts with 2 sec and 5 sec.
     */
    protected const GUZZLE_CONNECT_TIMEOUT = 10;

    protected const GUZZLE_TIMEOUT = 20;

    public function __construct(
        protected RequestFactory $requestFactory,
        protected ExtConf $extConf,
        protected LoggerInterface $logger,
    ) {}

    public function requestAll(
        RequestInterface $request,
        ?string $language = null,
    ): \Generator {
        $url = $this->extConf->getBaseUrl() . $request->getUrl();

        $headers = $request->getHeaders();
        $headers = $this->getHeaderWithAuthorization($headers);
        $headers = $this->getHeaderWithLanguage($headers, $language);

        $query = $request->getQuery();
        $query = $this->getQueryWithMandant($query);

        $currentPage = 0;
        $totalPages = 1;

        while ($currentPage < $totalPages) {
            $query = $this->updateQueryWithPagination($request, $query, $currentPage);

            $options = [
                'headers' => $headers,
                'body' => $request->getBody(),
                'query' => $query,
                'http_errors' => false,
            ];
            $options = $this->updateOptionsWithTimeout($options);

            $response = $this->requestFactory->request(
                $url,
                'GET',
                $options,
            );

            if ($response->getStatusCode() === 404) {
                $this->logger->error(
                    'Service BW API record was not found. The requested endpoint or record may not exist.',
                    [
                        'Status Code' => $response->getStatusCode(),
                        'URL' => $url,
                        'Query' => $query,
                    ],
                );

                break;
            }

            if ($response->getStatusCode() !== 200) {
                $this->logger->error(
                    'Service BW API responded with an unexpected status code.',
                    [
                        'Status Code' => $response->getStatusCode(),
                        'URL' => $url,
                        'Query' => $query,
                    ],
                );

                break;
            }

            $responseData = json_decode((string)$response->getBody(), true);

            // Prevent infinite loop if these values are not part of the response
            if (!isset($responseData['currentPage'], $responseData['totalPages'])) {
                break;
            }

            $currentPage = $responseData['currentPage'] + 1;
            $totalPages = $responseData['totalPages'];

            foreach ($responseData['items'] as $item) {
                yield (int)$item['id'] => $item;
            }
        }
    }

    /** @return array<string, mixed> */
    public function requestRecord(
        RequestInterface $request,
        string $language,
    ): array {
        $attempt = 0;

        $url = $this->extConf->getBaseUrl() . $request->getUrl();

        $headers = $request->getHeaders();
        $headers = $this->getHeaderWithAuthorization($headers);
        $headers = $this->getHeaderWithLanguage($headers, $language);

        $query = $request->getQuery();
        $query = $this->getQueryWithMandant($query);

        $options = [
            'headers' => $headers,
            'body' => $request->getBody(),
            'query' => $query,
            'http_errors' => false,
        ];
        $options = $this->updateOptionsWithTimeout($options);

        while (true) {
            try {
                $response = $this->requestFactory->request(
                    $url,
                    'GET',
                    $options,
                );

                if ($response->getStatusCode() >= 500 && $attempt < self::MAX_REQUEST_RETRIES) {
                    ++$attempt;
                    $this->waitBeforeRetry($attempt);

                    continue;
                }

                return json_decode((string)$response->getBody(), true);
            } catch (ConnectException $exception) {
                if ($attempt >= self::MAX_REQUEST_RETRIES) {
                    throw $exception;
                }

                ++$attempt;
                $this->waitBeforeRetry($attempt);
            } catch (RequestException $exception) {
                $response = $exception->getResponse();

                if (
                    $response instanceof ResponseInterface
                    && $response->getStatusCode() < 500
                ) {
                    throw $exception;
                }

                if ($attempt >= self::MAX_REQUEST_RETRIES) {
                    throw $exception;
                }

                ++$attempt;
                $this->waitBeforeRetry($attempt);
            }
        }
    }

    protected function waitBeforeRetry(int $attempt): void
    {
        usleep($attempt * 250_000);
    }

    /**
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    protected function getHeaderWithAuthorization(array $headers): array
    {
        $headers['Authorization'] = $this->extConf->getToken();

        return $headers;
    }

    /**
     * @param array<string, string> $headers
     * @param ?string $language The 2-letter ISO code like "de", or "en"
     * @return array<string, string>
     */
    protected function getHeaderWithLanguage(
        array $headers,
        ?string $language = null,
    ): array {
        $sanitizedLanguage = is_string($language) ? trim($language) : '';

        if ($sanitizedLanguage !== '') {
            $headers['Accept-Language'] = $sanitizedLanguage;
        }

        return $headers;
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    protected function getQueryWithMandant(array $query): array
    {
        $query['mandantId'] = $this->extConf->getMandant();

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    protected function updateQueryWithPagination(
        RequestInterface $request,
        array $query,
        int $currentPage,
    ): array {
        if ($request::SUPPORTS_PAGINATION) {
            $query['page'] = $currentPage;
            $query['pageSize'] = self::MAX_ITEMS_EACH_REQUEST;
        }

        return $query;
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function updateOptionsWithTimeout(array $options): array
    {
        $options['connect_timeout'] = self::GUZZLE_CONNECT_TIMEOUT;
        $options['timeout'] = self::GUZZLE_TIMEOUT;

        return $options;
    }
}
