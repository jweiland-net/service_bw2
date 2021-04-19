<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Helper;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Helper class to fetch a token and add it to sys_registry.
 *
 * @internal
 */
class TokenHelper implements SingletonInterface
{
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

    public function __construct(RequestFactory $requestFactory, Registry $registry, ExtConf $extConf)
    {
        $this->requestFactory = $requestFactory;
        $this->registry = $registry;
        $this->extConf = $extConf;
    }

    public function fetchAndSaveToken(): void
    {
        $response = $this->requestFactory->request(
            $this->extConf->getBaseUrl() . '/wsbenutzer/token',
            'POST',
            [
                'headers' => ['Accept' => 'text/plain', 'Content-Type' => 'text/plain', 'X-SP-Mandant' => $this->extConf->getMandant()],
                'body' => $this->extConf->getPassword(),
                'query' => ['benutzername' => $this->extConf->getUsername()]
            ]
        );
        // Guzzle throws exception if http status code is lower than 400!
        $this->registry->set('ServiceBw', 'token', $response->getBody()->getContents());
    }
}
