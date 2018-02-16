<?php
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

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AbstractInitializer
 *
 * @package JWeiland\ServiceBw2\IndexQueue\Initializer
 */
class AbstractInitializer extends \ApacheSolrForTypo3\Solr\IndexQueue\Initializer\AbstractInitializer
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * AbstractInitializer constructor.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Run initialization query
     *
     * @param string $initializationQuery
     * @return bool
     */
    protected function runInitializationQuery(string $initializationQuery): bool
    {
        $initialized = false;

        $logData = ['query' => $initializationQuery];

        try {
            $GLOBALS['TYPO3_DB']->sql_query($initializationQuery);
            $initialized = true;
        } catch (DBALException $DBALException) {
            $logData['error'] = $DBALException->getCode() . ': ' . $DBALException->getMessage();
        }

        $this->logInitialization($initializationQuery);

        return $initialized;
    }

    /**
     * Get SQL VALUES string for object of service_bw
     *
     * @param array $objects
     * @return string
     */
    protected function getValuesForObjects(array $objects): string
    {
        $values = '';
        foreach ($objects as $object) {
            $values .= $this->getValuesForObject($object) . ', ';
            if ($object['_children']) {
                $values .= $this->getValuesForObjects($object['_children']);
            }
        }

        return $values;
    }

    /**
     * Returns for solr index queue required values
     *
     * @param array $object
     * @return string
     */
    protected function getValuesForObject(array $object): string
    {
        return '('
            . $this->site->getRootPageId() . ', '
            . '\'' .$this->type . '\', '
            . $object['id'] . ', '
            . '\'' .$this->type . '\', '
            . 0 . ', '
            . time() . ', '
            . '\'\''
            . ')';
    }
}
