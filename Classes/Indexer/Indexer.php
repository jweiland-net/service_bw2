<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Indexer;

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Indexer
 */
class Indexer extends \ApacheSolrForTypo3\Solr\IndexQueue\Indexer
{
    /**
     * Adjust index item
     */
    protected function indexItem(Item $item, int $language = 0): bool
    {
        $itemIndexed = false;
        $documents = [];

        $itemDocument = $this->itemToDocument($item, $language);
        if ($itemDocument === null) {
            /*
             * If there is no itemDocument, this means there was no translation
             * for this record. This should not stop the current item to count as
             * being valid because not-indexing not-translated items is perfectly
             * fine.
             */
            return true;
        }

        $documents[] = $itemDocument;
        $documents = array_merge($documents, $this->getAdditionalDocuments($item, $language, $itemDocument));
        $documents = $this->processDocuments($item, $documents);
        $documents = $this->preAddModifyDocuments($item, $language, $documents);

        $response = $this->solr->getWriteService()->addDocuments($documents);
        if ($response->getHttpStatus() === 200) {
            $itemIndexed = true;
        }

        $this->log($item, $documents, $response);

        return $itemIndexed;
    }

    /**
     * Deletes items by type
     */
    public function deleteItemsByType(string $type, int $rootPageId): void
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        try {
            $site = $siteFinder->getSiteByRootPageId($rootPageId);
            foreach ($site->getLanguages() as $siteLanguage) {
                $this->solr = $this->connectionManager->getConnectionByRootPageId(
                    $rootPageId,
                    $siteLanguage->getLanguageId(),
                );
                $this->solr->getWriteService()->deleteByType($type);
            }
        } catch (SiteNotFoundException $siteNotFoundException) {
            $this->solr = $this->connectionManager->getConnectionByRootPageId($rootPageId);
            $this->solr->getWriteService()->deleteByType($type);
        }
    }
}
