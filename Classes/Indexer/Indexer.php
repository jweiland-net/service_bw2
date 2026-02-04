<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Indexer;

use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Indexer extends \ApacheSolrForTypo3\Solr\IndexQueue\Indexer
{
    public function deleteItemsByType(string $type, int $rootPageId): void
    {
        try {
            $site = $this->getSiteFinder()->getSiteByRootPageId($rootPageId);
            foreach ($site->getLanguages() as $siteLanguage) {
                $solrConnection = $this->connectionManager->getConnectionByRootPageId(
                    $rootPageId,
                    $siteLanguage->getLanguageId(),
                );
                $solrConnection->getWriteService()->deleteByType($type);
            }
        } catch (SiteNotFoundException $siteNotFoundException) {
            $solrConnection = $this->connectionManager->getConnectionByRootPageId($rootPageId);
            $solrConnection->getWriteService()->deleteByType($type);
        }
    }

    protected function getSiteFinder(): SiteFinder
    {
        return GeneralUtility::makeInstance(SiteFinder::class);
    }
}
