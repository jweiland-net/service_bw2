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
 * Class Search
 */
class Search extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = 'search';
    
    /**
     * @var string
     */
    protected $method = RequestInterface::METHOD_GET;
    
    /**
     * @var string
     */
    protected $accept = RequestInterface::ACCEPT_JSON;
    
    /**
     * @var array
     */
    protected $allowedParameters = array(
        'lang' => array(
            'dataType' => 'string',
            'default' => 'de',
            'required' => true
        ),
        'primaryIndex' => array(
            'dataType' => 'string',
        ),
        'q' => array(
            'dataType' => 'string',
            'required' => true
        ),
        'f' => array(
            'dataType' => 'string',
            'default' => 'all'
        ),
        's' => array(
            'dataType' => 'string',
            'default' => 'relevance'
        ),
        'secondaryIndices' => array(
            'dataType' => 'array'
        )
    );
}
