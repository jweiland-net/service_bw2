<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Request\Leistungen;

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
 * Class Leistungen
 * Description provided by Service BW API documentation:
 * Listet alle Leistungen auf
 *
 * @package JWeiland\ServiceBw2\Request\Leistungen;
 */
class Leistungen extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/leistungen?page={page}&pageSize={pageSize}&sortProperty={sortProperty}'
    . '&sortDirection={sortDirection}';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'page' => [
            'dataType' => 'integer',
            'required' => true,
            'default' => 0
        ],
        'pageSize' => [
            'dataType' => 'integer',
            'required' => true,
            'default' => 1000
        ],
        'sortProperty' => [
            'dataType' => 'string',
            'required' => true,
        ],
        'sortDirection' => [
            'dataType' => 'string',
            'required' => true
        ]
    ];
}
