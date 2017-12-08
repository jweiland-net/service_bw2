<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\ViewHelpers;

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

use JWeiland\Maps2\Domain\Model\Location;
use JWeiland\Maps2\Domain\Model\RadiusResult;
use JWeiland\Maps2\Utility\GeocodeUtility;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitRepository;
use JWeiland\ServiceBw2\Request\Organisationseinheiten\Id;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to get the uid of a poi collection for passed organisationseinheit
 * This ViewHelper will automatically create a new poi collection if there is no relation
 * while calling the ViewHelper or if the related record has another address than current
 * organisationseinheit item.
 *
 * @package JWeiland\ServiceBw2\ViewHelpers;
 */
class OrganisationseinheitPoiCollectionUidViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var ConfigurationManager
     */
    protected static $configurationManager;

    /**
     * @var OrganisationseinheitRepository
     */
    protected static $organisationseinheitRepository;

    /**
     * @var GeocodeUtility
     */
    protected static $geocodeUtility;

    /**
     * Storage page id of maps2 records
     *
     * @var int
     */
    protected static $maps2Pid = 0;

    /**
     * @var string
     */
    protected static $id = '';

    /**
     * Initializes the arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('id', 'int', 'id of the organisationseinheit', true);
    }

    /**
     * @param int $organisationseinheitId
     * @return void
     */
    public static function init(int $organisationseinheitId)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        self::$configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        self::$organisationseinheitRepository = $objectManager->get(OrganisationseinheitRepository::class);
        self::$geocodeUtility = $objectManager->get(GeocodeUtility::class);
        self::$maps2Pid = self::$configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_FRAMEWORK
        )['settings']['organisationseinheit']['maps2Pid'];
        self::$id = $organisationseinheitId;
    }

    /**
     * Returns the uid of a maps2 poi collection for a organisationseinheit.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        self::init($arguments['id']);
        try {
            $organisationseinheit = self::$organisationseinheitRepository->getById($arguments['id']);
        } catch (\Exception $exception) {
            GeneralUtility::sysLog(
                'Exception inside ' . __CLASS__ . ': ' . $exception->getMessage(),
                'service_bw2',
                GeneralUtility::SYSLOG_SEVERITY_ERROR
            );
            return 0;
        }
        $maps2Relation = self::findMaps2Relation();
        $address = self::getAddress($organisationseinheit);
        $hashedAddress = md5($address);
        if (is_array($maps2Relation) && !empty($maps2Relation)) {
            if ($maps2Relation['hashed_address'] === $hashedAddress) {
                $maps2PoiUid = $maps2Relation['tx_maps2_poi'];
            } else {
                $maps2PoiUid = self::getUidOfNewPoiCollectionForAddress($address, $organisationseinheit['name']);
                self::updatePoiRelation($hashedAddress, $maps2PoiUid);
            }
        } else {
            $maps2PoiUid = self::getUidOfNewPoiCollectionForAddress($address, $organisationseinheit['name']);
            self::createPoiRelation($hashedAddress, $maps2PoiUid);
        }
        return $maps2PoiUid;
    }

    /**
     * Find maps2 relation in database
     *
     * @return array|bool array with hashed_address and tx_maps2_poi, false on failure (also if there is no relation)
     */
    protected static function findMaps2Relation()
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_servicebw2_organisationseinheit');
        $result = $connection
            ->select(
                ['hashed_address', 'tx_maps2_poi'],
                'tx_servicebw2_organisationseinheit',
                ['id' => self::$id]
            )->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Update maps2 poi collection relation
     *
     * @param string $hashedAddress
     * @param int $txMaps2Poi
     * @return void
     */
    protected static function updatePoiRelation(string $hashedAddress, int $txMaps2Poi)
    {
        $data = ['hashed_address' => $hashedAddress, 'tx_maps2_poi' => $txMaps2Poi];
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_servicebw2_organisationseinheit')
            ->update('tx_servicebw2_organisationseinheit', $data, ['id' => self::$id]);
    }

    /**
     * Create maps2 poi collection relation
     *
     * @param string $hashedAddress
     * @param int $txMaps2Poi
     * @return void
     */
    protected static function createPoiRelation(string $hashedAddress, int $txMaps2Poi)
    {
        $data = ['id' => self::$id, 'hashed_address' => $hashedAddress, 'tx_maps2_poi' => $txMaps2Poi];
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_servicebw2_organisationseinheit')
            ->insert('tx_servicebw2_organisationseinheit', $data);
    }

    /**
     * Get address from organisationseinheit
     *
     * @param array $organisationseinheit
     * @return string
     */
    protected static function getAddress(array $organisationseinheit): string
    {
        if ($organisationseinheit['kommunikation']) {
            foreach ($organisationseinheit['anschrift'] as $anschrift) {
                if ($anschrift['type'] === 'HAUSANSCHRIFT') {
                    if (
                        $anschrift['strasse'] &&
                        $anschrift['hausnummer'] &&
                        $anschrift['postleitzahl'] &&
                        $anschrift['ort']
                    ) {
                        $address = sprintf(
                            '%s %s %s %s',
                            $anschrift['strasse'],
                            $anschrift['hausnummer'],
                            $anschrift['postleitzahl'],
                            $anschrift['ort']
                        );
                        return $address;
                    }
                }
            }
        }
        return '';
    }

    /**
     * Returns the uid of a new created poi collection for $address
     *
     * @param string $address
     * @param string $poiTitle
     * @return int
     */
    protected static function getUidOfNewPoiCollectionForAddress(string $address, string $poiTitle): int
    {
        $poiUid = 0;
        $response = self::$geocodeUtility->findPositionByAddress($address);
        if ($response instanceof ObjectStorage && $response->count()) {
            /** @var RadiusResult $firstResult */
            $firstResult = $response->current();
            $location = $firstResult->getGeometry()->getLocation();
            $address = $firstResult->getFormattedAddress();
            $poiUid = self::createNewPoiCollection($location, $address, $poiTitle);
        }
        return $poiUid;
    }

    /**
     * Creates a new poi colleciton
     *
     * @param Location $location
     * @param string $address
     * @param string $poiTitle
     * @return int uid of the new poi collection
     */
    protected static function createNewPoiCollection(Location $location, string $address, string $poiTitle): int
    {
        $data = [];
        $data['pid'] = self::$maps2Pid;
        $data['tstamp'] = time();
        $data['crdate'] = time();
        $data['hidden'] = 0;
        $data['deleted'] = 0;
        $data['latitude'] = $location->getLat();
        $data['longitude'] = $location->getLng();
        $data['collection_type'] = 'Point';
        $data['title'] = $poiTitle;
        $data['address'] = $address;
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_maps2_domain_model_poicollection');
        $connection->insert('tx_maps2_domain_model_poicollection', $data);
        return (int)$connection->lastInsertId();
    }
}
