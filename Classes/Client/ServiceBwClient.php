<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *  Client to be used for all Service BW API v2 requests
 */
class ServiceBwClient implements LoggerAwareInterface, SingletonInterface
{
    use LoggerAwareTrait;

    protected const API_ENDPOINT = '/rest-v2/api';

    protected const DEFAULT_PAGINATION_CONFIGURATION = [
        'nextItem' => 'nextPage',
        'pageParameter' => 'page',
        'pageSizeParameter' => 'pageSize',
        'pageSize' => 1000
    ];

    protected const DEFAULT_LOCALIZATION_CONFIGURATION = [
        'headerParameter' => 'Accept-Language'
    ];

    protected $path = '';

    protected $isPaginatedRequest = false;
    protected $paginationConfiguration = self::DEFAULT_PAGINATION_CONFIGURATION;

    protected $isLocalizedRequest = false;
    protected $localizationConfiguration = self::DEFAULT_LOCALIZATION_CONFIGURATION;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @var FrontendInterface
     */
    protected $cache;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param RequestFactory $requestFactory
     * @param Registry $registry
     * @param TokenHelper $tokenHelper
     * @param ExtConf $extConf
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RequestFactory $requestFactory, Registry $registry, TokenHelper $tokenHelper, ExtConf $extConf, EventDispatcherInterface $eventDispatcher)
    {
        $this->requestFactory = $requestFactory;
        $this->registry = $registry;
        $this->extConf = $extConf;
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('servicebw_request');

        if (!$this->registry->get('ServiceBw', 'token', false)) {
            $tokenHelper->fetchAndSaveToken();
        }
    }

    public function request(
        string $path,
        array $getParameters = [],
        bool $isLocalizedRequest = true,
        bool $isPaginatedRequest = false,
        ?string $body = null,
        array $overridePaginationConfiguration = [],
        array $overrideLocalizationConfiguration = []
    ): array {
        $cacheIdentifier = $this->getCacheIdentifier([
            $path,
            $getParameters,
            $isLocalizedRequest,
            $isPaginatedRequest,
            $body,
            $overridePaginationConfiguration,
            $overrideLocalizationConfiguration
        ]);

        if (!$this->cache->has($cacheIdentifier)) {
            $this->path = $path;
            $this->isPaginatedRequest = $isPaginatedRequest;
            $this->isLocalizedRequest = $isLocalizedRequest;
            $this->paginationConfiguration = array_merge(self::DEFAULT_PAGINATION_CONFIGURATION, $overridePaginationConfiguration);
            $this->localizationConfiguration = array_merge(self::DEFAULT_LOCALIZATION_CONFIGURATION, $overrideLocalizationConfiguration);

            $query = array_merge(
                $this->getQueryForDefaultParameters(),
                $getParameters,
                $isPaginatedRequest ? $this->getQueryForPaginatedRequest() : []
            );

            $items = [];
            $isNextPageSet = false;
            do {
                try {
                    $responseBody = [];

                    $response = $this->requestFactory->request(
                        $this->extConf->getBaseUrl() . self::API_ENDPOINT . $this->path,
                        'GET',
                        [
                            'headers' => $this->getHeaders(),
                            'body' => $body,
                            'query' => $query
                        ]
                    );

                    if ($response->getStatusCode() !== 200) {
                        $this->logger->error('SKIP record because returned status code is not 200.');
                        continue;
                    }

                    $responseBody = (array)json_decode($response->getBody()->getContents(), true);

                    /** @var ModifyServiceBwResponseEvent $event */
                    $event = $this->eventDispatcher->dispatch(new ModifyServiceBwResponseEvent(
                        $path,
                        $responseBody,
                        $isPaginatedRequest,
                        $isLocalizedRequest
                    ));
                    $responseBody = $event->getResponseBody();

                    $isNextPageSet = false;
                    if ($isPaginatedRequest) {
                        if ($isNextPageSet = array_key_exists($this->paginationConfiguration['nextItem'], $responseBody)) {
                            $query[$this->paginationConfiguration['pageParameter']] = $responseBody[$this->paginationConfiguration['nextItem']];
                        }
                        array_push($items, ...$responseBody['items']);
                    }
                } catch (\Exception $exception) {
                    $this->logger->error('SKIP record because of error: ' . $exception->getMessage());
                }
            } while ($isPaginatedRequest && $isNextPageSet);

            $this->cache->set($cacheIdentifier, $isPaginatedRequest ? $items : $responseBody, ['service_bw2_request']);
        }

        return $this->cache->get($cacheIdentifier);
    }

    protected function getCacheIdentifier(array $requestArguments): string
    {
        $value = json_encode($requestArguments);
        if ($requestArguments[2]) {
            $value .= GeneralUtility::makeInstance(LocalizationHelper::class)->getFrontendLanguageIsoCode();
        }
        return md5($value);
    }

    protected function getHeaders(): array
    {
        $headers = [
            'Authorization' => $this->registry->get('ServiceBw', 'token', '')
        ];
        if ($this->isLocalizedRequest) {
            $localizationHelper = GeneralUtility::makeInstance(ObjectManager::class)->get(LocalizationHelper::class);
            $headers[$this->localizationConfiguration['headerParameter']] = $localizationHelper->getFrontendLanguageIsoCode();
        }
        return $headers;
    }

    protected function getQueryForPaginatedRequest(): array
    {
        return [
            $this->paginationConfiguration['pageParameter'] => 0,
            $this->paginationConfiguration['pageSizeParameter'] => $this->paginationConfiguration['pageSize']
        ];
    }

    protected function getQueryForDefaultParameters(): array
    {
        $query = ['mandantId' => $this->extConf->getMandant()];
        if ($this->extConf->getAgs()) {
            $query['gebietAgs'] = $this->extConf->getAgs();
        }
        if ($this->extConf->getGebietId()) {
            $query['gebietId'] = $this->extConf->getGebietId();
        }
        return $query;
    }
}
