<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
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
    protected Indexer $indexer;

    protected array $alreadyIndexed = [];

    public function __construct(Indexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function indexRecords(array $records, string $type, int $rootPageUid): void
    {
        foreach ($records as $record) {
            if (
                is_array($record)
                && !in_array($record['id'], $this->alreadyIndexed, true)
                && $this->indexRecord($record, $type, $rootPageUid)
            ) {
                $this->alreadyIndexed[] = $record['id'];
            }
        }
    }

    /**
     * Index service bw2 records
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
     * Wrapper for delete by type
     */
    public function indexerDeleteByType(string $type, int $rootPage): void
    {
        $tsfe = $GLOBALS['TSFE'] ?? null;
        $this->indexer->deleteItemsByType($type, $rootPage);
        $GLOBALS['TSFE'] = $tsfe;
    }
}
