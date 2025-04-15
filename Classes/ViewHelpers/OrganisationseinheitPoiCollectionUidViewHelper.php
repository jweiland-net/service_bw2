<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\ViewHelpers;

use JWeiland\Maps2\Domain\Model\Position;
use JWeiland\Maps2\Service\GeoCodeService;
use JWeiland\Maps2\Service\MapService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to get the uid of a poi collection for passed organisationseinheit
 * This ViewHelper will automatically create a new poi collection if there is no relation
 * while calling the ViewHelper or if the related record has another address than current
 * organisationseinheit item.
 */
class OrganisationseinheitPoiCollectionUidViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var ConfigurationManager
     */
    protected static $configurationManager;

    /**
     * @var GeoCodeService
     */
    protected static $geoCodeService;

    /**
     * Storage page id of maps2 records
     *
     * @var int
     */
    protected static $maps2Pid = 0;

    /**
     * @var int
     */
    protected static $id = 0;

    public function initializeArguments(): void
    {
        $this->registerArgument('organisationseinheit', 'array', 'organisationseinheit', true);
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public static function init(int $organisationseinheitId): void
    {
        self::$configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        self::$geoCodeService = GeneralUtility::makeInstance(GeoCodeService::class);
        self::$maps2Pid = (int)(self::$configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        )['settings']['organisationseinheiten']['maps2Pid'] ?? 0);

        self::$id = $organisationseinheitId;
    }

    /**
     * Returns the uid of a maps2 poi collection for a organisationseinheit.
     *
     * @throws InvalidConfigurationTypeException
     * @throws \Exception
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        self::init($arguments['organisationseinheit']['id']);
        $maps2Relation = self::findMaps2Relation();
        $address = self::getAddress($arguments['organisationseinheit']);
        $hashedAddress = md5($address);
        if (is_array($maps2Relation) && $maps2Relation !== []) {
            if ($maps2Relation['hashed_address'] === $hashedAddress) {
                $maps2PoiUid = $maps2Relation['tx_maps2_poi'];
            } else {
                $maps2PoiUid = self::getUidOfNewPoiCollectionForAddress($address, $arguments['organisationseinheit']['name']);
                self::updatePoiRelation($hashedAddress, $maps2PoiUid);
            }
        } else {
            $maps2PoiUid = self::getUidOfNewPoiCollectionForAddress($address, $arguments['organisationseinheit']['name']);
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

        return $connection
            ->select(
                ['hashed_address', 'tx_maps2_poi'],
                'tx_servicebw2_organisationseinheit',
                ['id' => self::$id]
            )
            ->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Update maps2 poi collection relation
     */
    protected static function updatePoiRelation(string $hashedAddress, int $txMaps2Poi): void
    {
        $data = ['hashed_address' => $hashedAddress, 'tx_maps2_poi' => $txMaps2Poi];
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_servicebw2_organisationseinheit')
            ->update('tx_servicebw2_organisationseinheit', $data, ['id' => self::$id]);
    }

    /**
     * Create maps2 poi collection relation
     */
    protected static function createPoiRelation(string $hashedAddress, int $txMaps2Poi): void
    {
        $data = [
            'id' => self::$id,
            'hashed_address' => $hashedAddress,
            'tx_maps2_poi' => $txMaps2Poi,
        ];

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_servicebw2_organisationseinheit')
            ->insert('tx_servicebw2_organisationseinheit', $data);
    }

    /**
     * Get address from organisationseinheit
     */
    protected static function getAddress(array $organisationseinheit): string
    {
        if ($organisationseinheit['anschriften']) {
            foreach ($organisationseinheit['anschriften'] as $anschrift) {
                if (
                    ($anschrift['type'] === 'HAUSANSCHRIFT')
                    && $anschrift['strasse']
                    && $anschrift['hausnummer']
                    && $anschrift['postleitzahl']
                    && $anschrift['ort']
                ) {
                    return sprintf(
                        '%s %s %s %s',
                        $anschrift['strasse'],
                        $anschrift['hausnummer'],
                        $anschrift['postleitzahl'],
                        $anschrift['ort']
                    );
                }
            }
        }

        return '';
    }

    /**
     * Returns the uid of a new created poi collection for $address
     *
     * @throws \Exception
     */
    protected static function getUidOfNewPoiCollectionForAddress(string $address, string $poiTitle): int
    {
        $poiUid = 0;
        $position = self::$geoCodeService->getFirstFoundPositionByAddress($address);
        if ($position instanceof Position && self::$maps2Pid !== 0) {
            $mapService = GeneralUtility::makeInstance(MapService::class);
            $poiUid = $mapService->createNewPoiCollection(
                self::$maps2Pid,
                $position,
                [
                    'title' => $poiTitle,
                    'address' => $address,
                ]
            );
        }

        return $poiUid;
    }
}
