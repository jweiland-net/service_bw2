<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use JWeiland\ServiceBw2\Client\Helper\LocalizationHelper;
use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;

/**
 * Client to be used for all Service BW API v2 requests
 */
readonly class ServiceBwClient
{
    protected const API_ENDPOINT = '/rest-v2/api';

    protected const DEFAULT_PAGINATION_CONFIGURATION = [
        'nextItem' => 'nextPage',
        'pageParameter' => 'page',
        'pageSizeParameter' => 'pageSize',
        'pageSize' => 1000,
    ];

    protected const DEFAULT_LOCALIZATION_CONFIGURATION = [
        'headerParameter' => 'Accept-Language',
    ];

    /**
     * @var string[]
     */
    public function __construct(
        protected RequestFactory $requestFactory,
        protected Registry $registry,
        protected ExtConf $extConf,
        protected EventDispatcherInterface $eventDispatcher,
        protected LocalizationHelper $localizationHelper,
        protected TokenHelper $tokenHelper,
        protected FrontendInterface $cache,
        protected LoggerInterface $logger,
    ) {
        if (!$this->registry->get('ServiceBw', 'token', false)) {
            $this->tokenHelper->fetchAndSaveToken();
        }
    }

    public function request(
        string $path,
        array $getParameters = [],
        bool $isLocalizedRequest = true,
        bool $isPaginatedRequest = false,
        ?string $body = null,
        array $overridePaginationConfiguration = [],
        array $overrideLocalizationConfiguration = [],
    ): array {
        $cacheIdentifier = $this->getCacheIdentifier([
            $path,
            $getParameters,
            $isLocalizedRequest,
            $isPaginatedRequest,
            $body,
            $overridePaginationConfiguration,
            $overrideLocalizationConfiguration,
        ]);

        // Early return, if data exists in cache
        if ($this->cache->has($cacheIdentifier)) {
            return $this->cache->get($cacheIdentifier);
        }

        $paginationConfiguration = $this->getPaginationConfiguration($overridePaginationConfiguration);
        $localizationConfiguration = $this->getLocalizationConfiguration($overrideLocalizationConfiguration);
        $queryPartForRequest = $this->getQueryPartForRequest(
            $getParameters,
            $isPaginatedRequest,
            $paginationConfiguration,
        );

        $items = [];
        $isNextPageSet = false;
        do {
            try {
                $responseBody = [];

                $response = $this->requestFactory->request(
                    $this->extConf->getBaseUrl() . self::API_ENDPOINT . $path,
                    'GET',
                    [
                        'headers' => $this->getHeaders($isLocalizedRequest, $localizationConfiguration),
                        'body' => $body,
                        'query' => $queryPartForRequest,
                    ],
                );

                if ($response->getStatusCode() !== 200) {
                    $this->logger->error('SKIP record because returned status code is not 200.');
                    continue;
                }

                try {
                    $responseBody = (array)json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $jsonException) {
                    $this->logger->error('SKIP record because Service BW response could not be extracted.');
                    continue;
                }

                /** @var ModifyServiceBwResponseEvent $event */
                $event = $this->eventDispatcher->dispatch(new ModifyServiceBwResponseEvent(
                    $path,
                    $responseBody,
                    $isPaginatedRequest,
                    $isLocalizedRequest,
                ));

                $responseBody = $event->getResponseBody();

                $isNextPageSet = false;
                if ($isPaginatedRequest) {
                    if ($isNextPageSet = array_key_exists($paginationConfiguration['nextItem'], $responseBody)) {
                        $queryPartForRequest[$paginationConfiguration['pageParameter']] = $responseBody[$paginationConfiguration['nextItem']];
                    }

                    array_push($items, ...$responseBody['items']);
                }
            } catch (\Exception $exception) {
                $this->logger->error('SKIP record because of error: ' . $exception->getMessage());
            }

        } while ($isPaginatedRequest && $isNextPageSet);

        $this->cache->set(
            $cacheIdentifier,
            $isPaginatedRequest ? $items : $responseBody,
            ['service_bw2_request'],
        );

        return $isPaginatedRequest ? $items : $responseBody;
    }

    protected function getCacheIdentifier(array $requestArguments): string
    {
        try {
            $value = json_encode($requestArguments, JSON_THROW_ON_ERROR);
        } catch (\JsonException $jsonException) {
            return '';
        }

        if ($requestArguments[2]) {
            $value .= $this->localizationHelper->getFrontendLanguageIsoCode();
        }

        return md5($value);
    }

    protected function getHeaders(bool $isLocalizedRequest, array $localizationConfiguration): array
    {
        $headers = [
            'Authorization' => $this->registry->get('ServiceBw', 'token', ''),
        ];

        if ($isLocalizedRequest) {
            $headers[$localizationConfiguration['headerParameter']] = $this->localizationHelper->getFrontendLanguageIsoCode();
        }

        return $headers;
    }

    protected function getQueryForPaginatedRequest(array $paginationConfiguration): array
    {
        return [
            $paginationConfiguration['pageParameter'] => 0,
            $paginationConfiguration['pageSizeParameter'] => $paginationConfiguration['pageSize'],
        ];
    }

    protected function getPaginationConfiguration(array $overridePaginationConfiguration): array
    {
        return array_merge(
            self::DEFAULT_PAGINATION_CONFIGURATION,
            $overridePaginationConfiguration,
        );
    }

    protected function getLocalizationConfiguration(array $overrideLocalizationConfiguration): array
    {
        return array_merge(
            self::DEFAULT_LOCALIZATION_CONFIGURATION,
            $overrideLocalizationConfiguration,
        );
    }

    protected function getQueryPartForRequest(
        array $getParameters,
        bool $isPaginatedRequest,
        array $paginationConfiguration,
    ): array {
        return array_merge(
            $this->extConf->getDefaultQueryForRequest(),
            $getParameters,
            $isPaginatedRequest ? $this->getQueryForPaginatedRequest($paginationConfiguration) : [],
        );
    }
}
