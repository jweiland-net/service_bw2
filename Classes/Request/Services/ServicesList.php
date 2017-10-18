<?php
namespace JWeiland\ServiceBw2\Request\Services;

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

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Token
 *
 * @package JWeiland\ServiceBw2\Request\Services
 */
class ServicesList extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/leistungen';

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
            'default' => 'asc',
            'required' => false
        ],
        'sortProperty' => [
            'dataType' => 'string',
            'default' => 'familienname',
            'required' => false
        ],
        'searchKey' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
        'searchColumns' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
    ];
}
