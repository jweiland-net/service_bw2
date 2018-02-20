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

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;

/**
 * Class LebenslagenIndexer
 *
 * @package JWeiland\ServiceBw2\IndexQueue
 */
class LebenslagenIndexer extends AbstractIndexer
{
    /**
     * Index OrganisationsEinheit
     *
     * @param Item $item
     * @return bool|void
     * @throws \Exception
     */
    public function index(Item $item)
    {
        $lebenslagenRepository = $this->objectManager->get(LebenslagenRepository::class);

        $lebenslagen = $lebenslagenRepository->getById($item->getRecordUid());

        $item->setRecord($this->resolveRecordProperties($lebenslagen, $item->getRecord()));

        parent::index($item);
    }
}
