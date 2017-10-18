<?php
namespace JWeiland\ServiceBw2\Request\ContactPersons;

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
 * @package JWeiland\ServiceBw2\Request\Areas
 */
class AreasIdsByAgs extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/gebiete/idsByAgs';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'ags' => [
            'dataType' => 'string',
            'default' => '',
            'required' => true
        ],
    ];

    /**
     * Set AGS
     * AGS: Amtlicher Gemeindeschluessel
     * Hint: prepended 0 is not allowed. That why I cast value to int and back to string
     *
     * @param string $ags
     *
     * @return void
     */
    public function setAgs($ags)
    {
        $this->addParameter('ags', (string)(int)$ags);
    }
}
