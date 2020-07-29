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

/**
 * Class Indexer
 */
class Indexer extends \ApacheSolrForTypo3\Solr\IndexQueue\Indexer
{
    /**
     * Adjust index item
     *
     * @param Item $item
     * @param int $language
     * @return bool
     * @throws \Apache_Solr_HttpTransportException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    protected function indexItem(Item $item, $language = 0): bool
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

        $response = $this->solr->addDocuments($documents);
        if ($response->getHttpStatus() === 200) {
            $itemIndexed = true;
        }

        $this->log($item, $documents, $response);

        return $itemIndexed;
    }

    /**
     * Deletes items by type
     *
     * @param string $type
     * @param int $rootPageId
     */
    public function deleteItemsByType(string $type, int $rootPageId): void
    {
        $this->solr = $this->connectionManager->getConnectionByRootPageId($rootPageId);
        $this->solr->deleteByType($type);
    }
}
