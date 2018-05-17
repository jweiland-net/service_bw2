<?php
namespace JWeiland\ServiceBw2\Request\WsBenutzer;

/*
 * This file is part of the TYPO3 CMS project.
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
     *
     * @return void
     */
    public function getScopes()
    {
        $this->setMethod('GET');
        $this->setPath('wsbenutzer/token/scopes');
    }

    /**
     * get authentication token
     *
     * @return void
     */
    public function getToken()
    {
        $this->setMethod('POST');
        $this->setPath('wsbenutzer/token/{benutzername}');
        $this->addParameter('benutzername', $this->extConf->getUsername());
        $this->setBody($this->extConf->getPassword());
    }
}
