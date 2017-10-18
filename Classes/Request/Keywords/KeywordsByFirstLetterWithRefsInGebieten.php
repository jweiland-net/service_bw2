<?php
namespace JWeiland\ServiceBw2\Request\Keywords;

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
 * @package JWeiland\ServiceBw2\Request\Keywords
 */
class KeywordsByFirstLetterWithRefsInGebieten extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/stichworte/{lang}/byFirstLetterWithRefsInGebieten';

    /**
     * It seems that gebietId will only work as long, as you set a firstLetter
     *
     * @var array
     */
    protected $allowedParameters = [
        'lang' => [
            'dataType' => 'string',
            'default' => 'de',
            'required' => true
        ],
        'firstLetter' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
        'gebietId' => [
            'dataType' => 'string',
            'default' => '',
            'required' => false
        ],
    ];
}
