<?php
namespace JWeiland\ServiceBw2\Request\OrganisationsEinheiten;

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

use JWeiland\ServiceBw2\PostProcessor\ZugehoerigeBehoerdePostProcessor;
use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Class Children
 *
 * @package JWeiland\ServiceBw2\Request\OrganisationsEinheiten
 */
class Children extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/organisationseinheiten/{id}/children';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'id' => [
            'dataType' => 'integer',
            'default' => 0,
            'required' => true
        ]
    ];
}
