<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

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
