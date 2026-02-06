<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Service;

use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\Domain\Site\Site;
use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use ApacheSolrForTypo3\Solr\Exception\InvalidArgumentException;
use ApacheSolrForTypo3\Solr\Exception\InvalidConnectionException;
use ApacheSolrForTypo3\Solr\IndexQueue\Indexer;
use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use ApacheSolrForTypo3\Solr\IndexQueue\Queue;
use ApacheSolrForTypo3\Solr\System\Configuration\TypoScriptConfiguration;
use Doctrine\DBAL\Exception as DBALException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

readonly class SolrIndexService
{
    public function __construct(
        protected SiteRepository $siteRepository,
        protected Queue $indexQueue,
        protected ConnectionManager $connectionManager,
    ) {}

    /**
     * Orchestrates a targeted cleanup of Solr documents for a specific record type.
     *
     * This wrapper is required because:
     * 1. API Gap: SolrWriteService::deleteByType() only targets the 'type' field.
     * It does not account for related file metadata indexed by EXT:solrfal.
     * 2. Data Integrity: Ensures that when a service_bw2 entity is removed or
     * reset, its associated 'fileReferenceType' entries are also purged to
     * prevent orphaned search results.
     * 3. Scope Control: Provides a safe entry point for custom Commands to
     * manipulate the index at the table/type level without triggering
     * global site-wide indexing resets.
     *
     * @param string $type The specific record type (usually the DB table name).
     * @param Site $site The site context used to resolve the Solr connection.
     *
     * @throws InvalidConnectionException
     */
    public function clearSolrIndexByType(string $type, Site $site): void
    {
        // Safety: Do not allow accidental global deletion
        if ($type === '') {
            return;
        }

        $tableName = $site->getSolrConfiguration()->getIndexQueueTypeOrFallbackToConfigurationName($type);

        $solrServers = $this->connectionManager->getConnectionsBySite($site);
        foreach ($solrServers as $solrServer) {
            // Delete solr documents
            $solrServer->getWriteService()->deleteByType($tableName);

            // Delete file references queued by solrfal
            if (ExtensionManagementUtility::isLoaded('solrfal')) {
                $solrServer->getWriteService()->deleteByQuery('fileReferenceType:' . $tableName);
            }
        }
    }

    public function indexServiceBWRecord(array $record, string $type, Site $solrSite): bool
    {
        $record['pid'] = $solrSite->getRootPageId();
        $record['uid'] = $record['id'];

        $item = new Item([
            'uid' => $record['id'],
            'item_uid' => $record['id'],
            'root' => $solrSite->getRootPageId(),
            'item_type' => $type,
            'indexing_configuration' => $type,
        ], $record);

        try {
            $indexed = $this->indexItem($item, $solrSite->getSolrConfiguration());
        } catch (\Throwable $e) {
            return false;
        }

        if ($record['_children']) {
            $this->indexServiceBWRecords($record['_children'], $type, $solrSite);
        }

        return $indexed;
    }

    protected function indexServiceBWRecords(
        array $records,
        string $type,
        Site $solrSite,
    ): void {
        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $this->indexServiceBWRecord($record, $type, $solrSite);
        }
    }

    /**
     * This method is adapted from EXT:solr's IndexService::indexItem().
     * Since the original method is protected, it is replicated here
     * to provide access for this extension.
     *
     * @throws \Throwable
     */
    protected function indexItem(Item $item, TypoScriptConfiguration $configuration): bool
    {
        $indexer = $this->getSolrIndexer($item, $configuration);

        // Remember original http host value
        $originalHttpHost = $_SERVER['HTTP_HOST'] ?? null;

        $itemChangedDate = $item->getChanged();
        $itemChangedDateAfterIndex = 0;

        try {
            $this->initializeHttpServerEnvironment($item);
            $itemIndexed = $indexer->index($item);

            // update IQ item so that the IQ can determine what's been indexed already
            if ($itemIndexed) {
                $this->indexQueue->updateIndexTimeByItem($item);
                $itemChangedDateAfterIndex = $item->getChanged();
            }

            if ($itemChangedDateAfterIndex > $itemChangedDate && $itemChangedDateAfterIndex > time()) {
                $this->indexQueue->setForcedChangeTimeByItem($item, $itemChangedDateAfterIndex);
            }
        } catch (\Throwable $e) {
            $this->restoreOriginalHttpHost($originalHttpHost);
            throw $e;
        }

        $this->restoreOriginalHttpHost($originalHttpHost);

        return $itemIndexed;
    }

    /**
     * All following methods are copies of EXT:solr IndexService methods
     * to get the indexItem method from above working.
     */

    /**
     * Initializes the $_SERVER['HTTP_HOST'] environment variable in CLI
     * environments dependent on the Index Queue item's root page.
     * When the Index Queue Worker task is executed by a cron job there is no
     * HTTP_HOST since we are in a CLI environment. RealURL needs the host
     * information to generate a proper URL, though. Using the Index Queue item's
     * root page information, we can determine the correct host although being
     * in a CLI environment.
     *
     * @param Item $item
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    protected function initializeHttpServerEnvironment(Item $item): void
    {
        static $hosts = [];
        $rootPageId = $item->getRootPageUid();
        $hostFound = !empty($hosts[$rootPageId]);

        if (!$hostFound) {
            $hosts[$rootPageId] = $item->getSite()->getDomain();
        }

        $_SERVER['HTTP_HOST'] = $hosts[$rootPageId];

        // needed since TYPO3 7.5
        GeneralUtility::flushInternalRuntimeCaches();
    }

    protected function restoreOriginalHttpHost(?string $originalHttpHost): void
    {
        if (!is_null($originalHttpHost)) {
            $_SERVER['HTTP_HOST'] = $originalHttpHost;
        } else {
            unset($_SERVER['HTTP_HOST']);
        }

        // needed since TYPO3 7.5
        GeneralUtility::flushInternalRuntimeCaches();
    }

    protected function getSolrIndexer(Item $item, TypoScriptConfiguration $solrConfiguration): Indexer
    {
        $indexerClass = $solrConfiguration->getIndexQueueIndexerByConfigurationName(
            $item->getIndexingConfigurationName()
        );

        $indexerConfiguration = $solrConfiguration->getIndexQueueIndexerConfigurationByConfigurationName(
            $item->getIndexingConfigurationName()
        );

        return GeneralUtility::makeInstance($indexerClass, $indexerConfiguration);
    }
}
