<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Service;

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use JWeiland\ServiceBw2\Indexer\Indexer;

/**
 * Class SolrIndexService
 */
class SolrIndexService
{
    /**
     * @var Indexer
     */
    protected $indexer;

    /**
     * @var array
     */
    protected $alreadyIndexed = [];

    /**
     * @param Indexer $indexer
     */
    public function injectIndexer(Indexer $indexer): void
    {
        $this->indexer = $indexer;
    }

    /**
     * Index records
     *
     * @param array $records
     * @param string $type
     * @param int $rootPageUid
     */
    public function indexRecords(array $records, string $type, int $rootPageUid): void
    {
        foreach ($records as $key => $record) {
            if ($record && !in_array($record['id'], $this->alreadyIndexed, true) && $this->indexRecord($record, $type, $rootPageUid)) {
                $this->alreadyIndexed[] = $record['id'];
            }
        }
    }

    /**
     * Index service bw2 records
     *
     * @param array $record
     * @param string $type equals the name of index config in TypoScript
     * @param int $rootPageUid
     * @return bool
     */
    public function indexRecord(array $record, string $type, int $rootPageUid): bool
    {
        $record['pid'] = $rootPageUid;
        $record['uid'] = $record['id'];

        $item = new Item([
            'uid' => $record['id'],
            'item_uid' => $record['id'],
            'root' => $rootPageUid,
            'item_type' => $type,
            'indexing_configuration' => $type
        ], $record);

        $indexed = $this->indexer->index($item);

        if ($record['_children']) {
            $this->indexRecords($record['_children'], $type, $rootPageUid);
        }

        return $indexed;
    }

    /**
     * Wrapper for index
     *
     * @param Item $item
     * @return bool
     */
    protected function indexerIndex(Item $item): bool
    {
        $tsfe = $GLOBALS['TSFE'];
        $indexed = $this->indexer->index($item);
        $GLOBALS['TSFE'] = $tsfe;
        return $indexed;
    }

    /**
     * Wrapper for delete by type
     *
     * @param string $type
     * @param int $rootPage
     */
    public function indexerDeleteByType(string $type, $rootPage): void
    {
        $tsfe = $GLOBALS['TSFE'];
        $this->indexer->deleteItemsByType($type, $rootPage);
        $GLOBALS['TSFE'] = $tsfe;
    }
}
