<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Indexer;

use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Indexing itself is done by EXT:solr's Indexer::indexItem(). This class only
 * adds the possibility to remove all documents of a service_bw2 type.
 */
class Indexer extends \ApacheSolrForTypo3\Solr\IndexQueue\Indexer
{
    /**
     * Deletes items by type
     */
    public function deleteItemsByType(string $type, int $rootPageId): void
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        try {
            $site = $siteFinder->getSiteByRootPageId($rootPageId);
            foreach ($site->getLanguages() as $siteLanguage) {
                $this->connectionManager
                    ->getConnectionByRootPageId($rootPageId, $siteLanguage->getLanguageId())
                    ->getWriteService()
                    ->deleteByType($type);
            }
        } catch (SiteNotFoundException $siteNotFoundException) {
            $this->connectionManager
                ->getConnectionByRootPageId($rootPageId)
                ->getWriteService()
                ->deleteByType($type);
        }
    }
}
