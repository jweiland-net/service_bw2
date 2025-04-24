<?php

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

// Icons registered through Icon Factory provider
return [
    'ext-servicebw-wizard-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/plugin_wizard.svg',
    ],
    'ext-servicebw2-organizationalunitslist-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_organizationalunitslist.svg',
    ],
    'ext-servicebw2-organizationalunitsshow-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_organizationalunitsshow.svg',
    ],
    'ext-servicebw2-serviceslist-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_serviceslist.svg',
    ],
    'ext-servicebw2-servicesshow-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_servicesshow.svg',
    ],
    'ext-servicebw2-lifesituationslist-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_lifesituationslist.svg',
    ],
    'ext-servicebw2-lifesituationsshow-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_lifesituationsshow.svg',
    ],
    'ext-servicebw2-servicebw2search-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:service_bw2/Resources/Public/Icons/servicebw2_servicebw2search.svg',
    ],
];
