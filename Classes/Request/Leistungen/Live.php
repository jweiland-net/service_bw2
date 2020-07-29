<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Leistungen;

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Live
 */
class Live extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/leistungen/{id}/{lang}/live';

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
