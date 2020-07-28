<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Gebiete;

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class IdsByAgs
 */
class IdsByAgs extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/gebiete/idsByAgs?ags={ags}';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'ags' => [
            'dataType' => 'integer',
            'required' => true
        ]
    ];
}
