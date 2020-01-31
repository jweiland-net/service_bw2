<?php
declare(strict_types = 1);
namespace JWeiland\ServiceBw2\Request\ExterneFormulare;

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
 * Class ListByLeistungAndRegion
 */
class ListByLeistungAndRegion extends AbstractRequest
{
    /**
     * @var string
     */
    protected $path = '/externeFormulare/listByLeistungAndRegion?leistungId={leistungId}&{regionIds}';

    /**
     * @var array
     */
    protected $allowedParameters = [
        'leistungId' => [
            'dataType' => 'integer',
            'required' => true
        ],
        'regionIds' => [
            'dataType' => 'string',
            'required' => true
        ]
    ];

    /**
     * RegionIds need a special URL handling.
     * URL has to look like: leistungId=1813&regionIds=2177&regionIds=1282&regionIds=1923
     *
     * @param array $regionIds
     */
    public function setRegionIds(array $regionIds)
    {
        $this->addParameter(
            'regionIds',
            implode(
                '&',
                array_map(function ($regionId) {
                    return 'regionIds=' . $regionId;
                }, $regionIds)
            )
        );
    }
}
