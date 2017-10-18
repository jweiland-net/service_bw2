<?php
namespace JWeiland\ServiceBw2\Request\Mandanten;

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
use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;
use JWeiland\ServiceBw2\Request\AbstractRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Token
 *
 * @package JWeiland\ServiceBw2\Request\Mandanten
 */
class MandantenList extends AbstractRequest
{
    /**
     * @var array
     */
    protected $allowedParameters = [
        'page' => [
            'dataType' => 'integer',
            'default' => 0,
            'required' => false
        ],
        'pageSize' => [
            'dataType' => 'integer',
            'default' => 1000,
            'required' => false
        ],
        'sortDirection' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
        'sortProperty' => [
            'dataType' => 'string',
            'default' => 'name',
            'required' => false
        ],
        'searchKey' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
        'searchColumns' => [
            'dataType' => 'array',
            'default' => [],
            'required' => false
        ],
        'body' => [
            'dataType' => 'array',
            'default' => [],
            'required' => true
        ],
    ];
}
