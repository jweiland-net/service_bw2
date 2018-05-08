<?php
namespace JWeiland\ServiceBw2\Request;

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

use JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor;

/**
 * Class Search
 */
class Search extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = 'search/{lang}?q={q}&f={f}&s={s}&position={position}';

    /**
     * @var string
     */
    protected $method = RequestInterface::METHOD_GET;

    /**
     * @var string
     */
    protected $accept = RequestInterface::ACCEPT_JSON;

    /**
     * @var bool
     */
    protected $clearDefaultPostProcessorClassNames = true;

    /**
     * @var array
     */
    protected $additionalPostProcessorClassNames = [
        JsonPostProcessor::class
    ];

    /**
     * @var array
     */
    protected $allowedParameters = [
        'lang' => [
            'dataType' => 'string',
            'default' => 'de',
            'required' => true
        ],
        'primaryIndex' => [
            'dataType' => 'string',
        ],
        'q' => [
            'dataType' => 'string',
            'required' => true
        ],
        'f' => [
            'dataType' => 'string',
            'default' => 'all'
        ],
        's' => [
            'dataType' => 'string',
            'default' => 'relevance'
        ],
        'secondaryIndices' => [
            'dataType' => 'array'
        ],
        'position' => [
            'dataType' => 'string'
        ]
    ];
}
