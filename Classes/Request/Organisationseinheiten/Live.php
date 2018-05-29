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

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Live
 */
class Live extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/organisationseinheiten/{id}/{lang}/live';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'id' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'lang' => [
            'dataType' => 'string',
            'required' => true
        ]
    ];
}
