<?php
declare(strict_types=1);
namespace JWeiland\ServiceBw2\IndexQueue\Initializer;

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

use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;

/**
 * Class LebenslagenInitializer
 *
 * @package JWeiland\ServiceBw2\IndexQueue\Initializer
 */
class LebenslagenInitializer extends AbstractInitializer
{
    /**
     * Run initializer
     *
     * @return bool
     * @throws \Exception
     */
    public function initialize(): bool
    {
        $lebenslagenRepository = $this->objectManager->get(LebenslagenRepository::class);

        $lebenslagen = $lebenslagenRepository->getAll();

        $initializationQuery = 'INSERT INTO tx_solr_indexqueue_item '
            . '(root, item_type, item_uid, indexing_configuration, indexing_priority, changed, errors) '
            . 'VALUES ' . rtrim($this->getValuesForObjects($lebenslagen), ', ');

        return $this->runInitializationQuery($initializationQuery);
    }
}
