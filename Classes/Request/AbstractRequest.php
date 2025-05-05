<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

use JWeiland\ServiceBw2\Client\ServiceBwClient;

/**
 * Base class for API requests to Service BW API
 */
abstract class AbstractRequest
{
    protected ServiceBwClient $client;

    public function __construct(ServiceBwClient $serviceBwClient)
    {
        $this->client = $serviceBwClient;
    }
}
