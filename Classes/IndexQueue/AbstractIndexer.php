<?php
namespace JWeiland\ServiceBw2\IndexQueue;

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

use ApacheSolrForTypo3\Solr\Domain\Variants\IdBuilder;
use ApacheSolrForTypo3\Solr\IndexQueue\Indexer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AbstractIndexer
 *
 * @package JWeiland\ServiceBw2\IndexQueue
 */
class AbstractIndexer extends Indexer
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * AbstractIndexer constructor.
     *
     * @param array $options
     * @param IdBuilder|null $idBuilder
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = [], IdBuilder $idBuilder = null)
    {
        parent::__construct($options, $idBuilder);
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Resolve record properties
     *
     * @param array $objects
     * @param array $record
     * @return array
     */
    protected function resolveRecordProperties(array $objects, array $record): array
    {
        $record['pid'] = $this->options['detailPage'];

        foreach ($objects as $property => $value)
        {
            if (\in_array($property, explode(',', $this->options['allowedProperties']), true)) {
                $record[$property] = $value;
            }
        }

        return $record;
    }
}
