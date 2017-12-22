<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Request\ExterneFormulare;

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

use JWeiland\ServiceBw2\PostProcessor\SharedStatusPostProcessor;
use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class ListByLeistungAndRegion
 *
 * @package JWeiland\ServiceBw2\Request\ExterneFormulare;
 */
class ListByLeistungAndRegion extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/externeFormulare/listByLeistungAndRegion?leistungId={leistungId}&regionIds={regionIds}';

    /**
     * Add request related PostProcessors
     *
     * @var array
     */
    protected $additionalPostProcessorClassNames = [
        SharedStatusPostProcessor::class
    ];

    /**
     * @var array
     */
    protected $allowedParameters = [
        'leistungId' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'regionIds' => [
            'dataType' => 'string',
            'required' => true
        ]
    ];
}
