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
use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class OrganisationsEinheitenIndexer
 *
 * @package JWeiland\ServiceBw2\IndexQueue
 */
class OrganisationsEinheitenIndexer extends Indexer
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var OrganisationseinheitenRepository
     */
    protected $organisationsEinheitenRepository;

    /**
     * OrganisationsEinheitenIndexer constructor.
     *
     * @param array $options
     * @param IdBuilder|null $idBuilder
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = [], IdBuilder $idBuilder = null)
    {
        parent::__construct($options, $idBuilder);

        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->organisationsEinheitenRepository = $this->objectManager->get(OrganisationseinheitenRepository::class);
    }

    public function index(Item $item)
    {
        $organisationsEinheit = $this->organisationsEinheitenRepository->getById($item->getRecordUid());
        $allowedProperties = explode(',', $this->options['allowedProperties']);

        $record = $item->getRecord();
        $record['pid'] = $this->options['detailPage'];

        foreach($organisationsEinheit as $property => $value) {
            if (in_array($property, $allowedProperties, true)) {
                $record[$property] = $value;
            }
        }

        $item->setRecord($record);

        parent::index($item);
    }
}
