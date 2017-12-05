<?php
namespace JWeiland\ServiceBw2\Request;

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

/**
 * Class Organisationseinheit
 *
 * Description provided by service_bw API documentation:
 * Liefert alle Zuständigkeiten für eine bestimmte Organisationseinheit als paginierte Liste
 *
 * @package JWeiland\ServiceBw2\Request
 */
class Organisationseinheit extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/zustaendigkeiten/organisationseinheit/{organisationseinheitId}';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'organisationseinheitId' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'page' => [
            'dataType' => 'integer',
            'required' => false,
            'default' => 0
        ],
        'pageSize' => [
            'dataType' => 'integer',
            'required' => false,
            'default' => 1000
        ],
        'sortDirection' => [
            'dataType' => 'string',
            'required' => false,
            'default' => 'ASC'
        ],
        'sortProperty' => [
            'dataType' => 'string',
            'required' => false,
            'default' => 'createDate'
        ]
    ];
}
