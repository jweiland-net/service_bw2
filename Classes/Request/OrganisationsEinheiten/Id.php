<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Request\Organisationseinheiten;

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

use JWeiland\ServiceBw2\PostProcessor\PublishStatusPostProcessor;
use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Id
 *
 * @package JWeiland\ServiceBw2\Request\Organisationseinheiten
 */
class Id extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/organisationseinheiten/{id}';

    /**
     * Add request related PostProcessors
     *
     * @var array
     */
    protected $additionalPostProcessorClassNames = [
        PublishStatusPostProcessor::class
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
