<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Organisationseinheiten;

use JWeiland\ServiceBw2\PostProcessor\LinkStatusPostProcessor;
use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Internetadressen
 */
class Internetadressen extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/organisationseinheiten/{id}/internetadressen';

    /**
     * Add request related PostProcessors
     *
     * @var array
     */
    protected $additionalPostProcessorClassNames = [
        LinkStatusPostProcessor::class
    ];

    /**
     * @var array
     */
    protected $allowedParameters = [
        'id' => [
            'dataType' => 'integer',
            'required' => true
        ]
    ];
}
