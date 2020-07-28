<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\WsBenutzer;

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Token
 */
class Token extends AbstractRequest
{
    /**
     * Token response is a plain string
     *
     * @var string
     */
    protected $accept = 'text/plain';

    /**
     * Do not load default post processors
     *
     * @var bool
     */
    protected $clearDefaultPostProcessorClassNames = true;

    /**
     * @var array
     */
    protected $allowedParameters = [
        'benutzername' => [
            'dataType' => 'string',
            'default' => 'de',
            'required' => true
        ]
    ];

    /**
     * Get Scopes
     *
     * Results in something like [readLebenslage, search]
     */
    public function getScopes(): void
    {
        $this->setMethod('GET');
        $this->setPath('wsbenutzer/token/scopes');
    }

    /**
     * Get authentication token
     */
    public function getToken(): void
    {
        $this->setMethod('POST');
        $this->setPath('wsbenutzer/token?benutzername={benutzername}');
        $this->addParameter('benutzername', $this->extConf->getUsername());
        $this->setBody($this->extConf->getPassword());
    }
}
