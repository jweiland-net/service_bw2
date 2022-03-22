<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Hooks;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;

/**
 * Add button to clear Service BW caches
 */
class ClearCacheActionsHook implements ClearCacheActionsHookInterface
{
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        $cacheActions[] = [
            'id' => 'service_bw2',
            'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang.xlf:flush_servicebw_caches_title',
            'description' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang.xlf:flush_servicebw_caches_description',
            'href' => (string)$this->uriBuilder->buildUriFromRoute('tce_db', ['cacheCmd' => 'service_bw2']),
            'iconIdentifier' => 'apps-toolbar-menu-cache'
        ];
        $optionValues[] = 'service_bw2';
    }
}
