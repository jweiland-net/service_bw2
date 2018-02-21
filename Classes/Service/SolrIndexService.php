<?php
declare(strict_types=1);
namespace JWeiland\ServiceBw2\Service;

/*
 * This file is part of the TYPO3 CMS project.
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

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use JWeiland\ServiceBw2\Indexer\Indexer;

/**
 * Class SolrIndexService
 *
 * @package JWeiland\ServiceBw2\Service
 */
class SolrIndexService
{
    /**
     * @var Indexer
     */
    protected $indexer;

    /**
     * injects indexer
     *
     * @param Indexer $indexer
     * @return void
     */
    public function injectIndexer(Indexer $indexer)
    {
        $this->indexer = $indexer;
    }

    /**
     * Index records
     *
     * @param array $records
     * @param string $type
     * @return void
     */
    public function indexRecords(array $records, string $type)
    {
        foreach ($records as $record) {
            $this->indexRecord($record, $type);
        }
    }

    /**
     * Index service bw2 records
     *
     * @param array $record
     * @param string $type equals the name of index config in TypoScript
     * @return bool
     */
    public function indexRecord(array $record, string $type): bool
    {
        $record['pid'] = $GLOBALS['TSFE']->rootLine[0]['uid'];
        $record['uid'] = $record['id'];

        $item = new Item([
            'uid' => $record['id'],
            'item_uid' => $record['id'],
            'root' => $GLOBALS['TSFE']->rootLine[0]['uid'],
            'item_type' => $type,
            'indexing_configuration' => $type
        ], $record);

        $tsfe = $GLOBALS['TSFE'];
        $indexed = $this->indexer->index($item);
        $GLOBALS['TSFE'] = $tsfe;

        if ($record['_children']) {
            $this->indexRecords($record['_children'], $type);
        }

        return $indexed;
    }
}
