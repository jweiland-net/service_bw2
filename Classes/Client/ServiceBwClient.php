<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client;

use JWeiland\ServiceBw2\Client\Request\RequestInterface;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

/**
 * Client to be used for all Service BW API v2 requests
 */
readonly class ServiceBwClient
{
    /**
     * Max records to retrieve from Service BW API.
     */
    public const MAX_ITEMS_EACH_REQUEST = 100;

    /**
     * I got a lot of connect timeouts with 2 sec and 5 sec.
     */
    public const GUZZLE_CONNECT_TIMEOUT = 10;

    public const GUZZLE_TIMEOUT = 20;

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

                continue;
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

            $currentPage = $responseData['currentPage'] + 1;
            $totalPages = $responseData['totalPages'];

            foreach ($responseData['items'] as $item) {
                yield (int)$item['id'] => $item;
            }
        }
    }

    public function requestRecord(
        RequestInterface $request,
        string $language,
    ): array {
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

        $response = $this->requestFactory->request(
            $url,
            'GET',
            $options,
        );

        if ($response->getStatusCode() !== 200) {
            $this->logger->error(
                'Service BW API responded with an unexpected status code.',
                [
                    'Status Code' => $response->getStatusCode(),
                    'URL' => $url,
                    'Query' => $query,
                ],
            );

            return [];
        }

        return json_decode((string)$response->getBody(), true);
    }

    protected function getHeaderWithAuthorization(array $headers): array
    {
        $headers['Authorization'] = $this->extConf->getToken();

        return $headers;
    }

    /**
     * @param ?string $language The 2-letter ISO code like "de", or "en"
     */
    protected function getHeaderWithLanguage(
        array $headers,
        ?string $language = null,
    ): array {
        if (
            is_string($language)
            && ($sanitizedLanguage = trim($language))
            && $sanitizedLanguage !== ''
        ) {
            $headers['Accept-Language'] = $sanitizedLanguage;
        }

        return $headers;
    }

    protected function getQueryWithMandant(array $query): array
    {
        $query['mandantId'] = $this->extConf->getMandant();

        return $query;
    }

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

    protected function updateOptionsWithTimeout(array $options): array
    {
        $options['connect_timeout'] = self::GUZZLE_CONNECT_TIMEOUT;
        $options['timeout'] = self::GUZZLE_TIMEOUT;

        return $options;
    }
}
