<?php
namespace JWeiland\ServiceBw2\Client;

/*
 * This file is part of the service_bw2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use GuzzleHttp\Client;
use JWeiland\ServiceBw2\Exception\HttpRequestException;
use JWeiland\ServiceBw2\Exception\HttpResponseException;
use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;
use JWeiland\ServiceBw2\Request\RequestInterface;
use JWeiland\ServiceBw2\Request\WsBenutzer\Token;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ServiceBwClient
 */
class ServiceBwClient
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Guzzle Client
     *
     * @var Client
     */
    protected $client;

    /**
     * Cache instance
     *
     * @var VariableFrontend
     */
    protected $cacheInstance;

    /**
     * @param ObjectManager $objectManager
     */
    public function injectObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param Registry $registry
     */
    public function injectRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Initializes this object
     * It starts a first call to Service BW and authenticate
     */
    public function initializeObject()
    {
        $this->cacheInstance = GeneralUtility::makeInstance(CacheManager::class)->getCache('servicebw_request');
        // set auth token in sys_registry
        if (!$this->registry->get('ServiceBw', 'token', false)) {
            /** @var Token $request */
            $request = $this->objectManager->get(Token::class);
            $request->getToken();
            $this->registry->set('ServiceBw', 'token', $this->processRequest($request));
        }
    }

    /**
     * Process request
     *
     * @param RequestInterface $request
     * @return null|array|string Returns null, if there is no data; returns array in most cases; returns string, if there are no PostProcessors like in Authentication (Bearer)
     * @throws \Exception if request is not valid or could not be decoded!
     */
    public function processRequest(RequestInterface $request)
    {
        $body = null;
        $cacheIdentifier = $this->getCacheIdentifier($request);
        // Check if current request is cached
        if ($this->cacheInstance->has($cacheIdentifier)) {
            $body = \json_decode($this->cacheInstance->get($cacheIdentifier), true);
            if ($body === null) {
                throw new HttpResponseException(
                    'Could not decode the JSON from HTTP response!',
                    1525852462
                );
            }
        } else {
            if (!$request->isValidRequest()) {
                throw new HttpRequestException('Request not valid', 1513940893);
            }
            if ($this->client === null) {
                $this->client = GeneralUtility::makeInstance(Client::class);
            }
            $response = $this->client->request(
                $request->getMethod(),
                $request->getUri(),
                [
                    'body' => $request->getBody(),
                    'headers' => $this->getHeaders($request)
                ]
            );
            if ($response->getStatusCode() === 200) {
                $body = (string)$response->getBody();
                foreach ($request->getPostProcessors() as $postProcessor) {
                    if ($postProcessor instanceof PostProcessorInterface) {
                        $body = $postProcessor->process($body);
                    }
                }
                $this->cacheInstance->set($cacheIdentifier, \json_encode($body), $request->getCacheTags());
            }
        }
        return $body;
    }

    /**
     * Get headers for request
     *
     * @param RequestInterface $request
     * @return array
     */
    protected function getHeaders(RequestInterface $request): array
    {
        $headers = [];
        $headers['X-SP-Mandant'] = $request->getMandant();
        if ($request->getAccept()) {
            $headers['Accept'] = $request->getAccept();
            if ($request->getAccept() === 'text/plain') {
                $headers['Content-Type'] = 'text/plain';
            }
        }
        $token = $this->registry->get('ServiceBw', 'token');
        if (!empty($token)) {
            $headers['Authorization'] = $token;
        }

        return $headers;
    }

    /**
     * Get a unique cache identifier for request
     *
     * @param RequestInterface $request
     * @return string
     */
    protected function getCacheIdentifier(RequestInterface $request): string
    {
        return md5(serialize($request));
    }
}
