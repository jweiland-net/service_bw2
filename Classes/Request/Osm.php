<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

/**
 * Class Osm
 */
class Osm extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = 'osm';

    /**
     * @var string
     */
    protected $method = RequestInterface::METHOD_GET;

    /**
     * @var string
     */
    protected $accept = RequestInterface::ACCEPT_XML;
}
