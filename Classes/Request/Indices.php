<?php
namespace JWeiland\ServiceBw2\Request;

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

/**
 * Class Indices
 */
class Indices extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = 'indices';

    /**
     * @var string
     */
    protected $method = RequestInterface::METHOD_GET;

    /**
     * @var string
     */
    protected $accept = RequestInterface::ACCEPT_JSON;
}
