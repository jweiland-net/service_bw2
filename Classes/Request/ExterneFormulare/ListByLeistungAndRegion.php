<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\ExterneFormulare;

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
    public function setRegionIds(array $regionIds): void
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
