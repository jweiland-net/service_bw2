<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to get the uid of a poi collection for passed organisationseinheit
 * This ViewHelper will automatically create a new poi collection if there is no relation
 * while calling the ViewHelper or if the related record has another address than the current
 * organisationseinheit item.
 */
final class OrganisationseinheitPoiCollectionUidViewHelper extends AbstractViewHelper
{
    private const TABLE = 'tx_servicebw2_organisationseinheit';

    /**
     * Storage page id of maps2 records
     */
    private int $maps2Pid = 0;

    private int $id = 0;

    public function __construct(
        private ConfigurationManagerInterface $configurationManager,
        private GeoCodeService $geoCodeService,
    ) {}

    public function initializeArguments(): void
    {
        $this->registerArgument('organisationseinheit', 'array', 'organisationseinheit', true);
    }

    /**
     * Returns the uid of a maps2 poi collection for a organisationseinheit.
     *
     * @throws \Exception
     */
    public function render(): int
    {
        $this->maps2Pid = (int)($this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
        )['settings']['organisationseinheiten']['maps2Pid'] ?? 0);

        $this->id = $this->arguments['organisationseinheit']['id'] ?? 0;

        $maps2Relation = $this->findMaps2Relation();
        $address = $this->getAddress($this->arguments['organisationseinheit']);
        $hashedAddress = md5($address);
        if (is_array($maps2Relation) && $maps2Relation !== []) {
            if ($maps2Relation['hashed_address'] === $hashedAddress) {
                $maps2PoiUid = $maps2Relation['tx_maps2_poi'];
            } else {
                $maps2PoiUid = $this->getUidOfNewPoiCollectionForAddress($address, $this->arguments['organisationseinheit']['name']);
                $this->updatePoiRelation($hashedAddress, $maps2PoiUid);
            }
        } else {
            $maps2PoiUid = $this->getUidOfNewPoiCollectionForAddress($address, $this->arguments['organisationseinheit']['name']);
            $this->createPoiRelation($hashedAddress, $maps2PoiUid);
        }

        return $maps2PoiUid;
    }

    /**
     * Find maps2 relation in the database
     *
     * @return array|bool array with hashed_address and tx_maps2_poi, false on failure (also if there is no relation)
     */
    private function findMaps2Relation(): array|bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(self::TABLE);

        return $connection
            ->select(
                ['hashed_address', 'tx_maps2_poi'],
                self::TABLE,
                [
                    'id' => $this->id,
                ],
            )
            ->fetchAssociative();
    }

    /**
     * Update maps2 poi collection relation
     */
    private function updatePoiRelation(string $hashedAddress, int $txMaps2Poi): void
    {
        $data = [
            'hashed_address' => $hashedAddress,
            'tx_maps2_poi' => $txMaps2Poi,
        ];

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(self::TABLE)
            ->update(
                self::TABLE,
                $data,
                [
                    'id' => $this->id,
                ],
            );
    }

    /**
     * Create maps2 poi collection relation
     */
    private function createPoiRelation(string $hashedAddress, int $txMaps2Poi): void
    {
        $data = [
            'id' => $this->id,
            'hashed_address' => $hashedAddress,
            'tx_maps2_poi' => $txMaps2Poi,
        ];

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(self::TABLE)
            ->insert(self::TABLE, $data);
    }

    /**
     * Get address from organisationseinheit
     */
    private function getAddress(array $organisationseinheit): string
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
                        $anschrift['ort'],
                    );
                }
            }
        }

        return '';
    }

    /**
     * Returns the uid of a newly created poi collection for $address
     *
     * @throws \Exception
     */
    private function getUidOfNewPoiCollectionForAddress(string $address, string $poiTitle): int
    {
        $poiUid = 0;
        $position = $this->geoCodeService->getFirstFoundPositionByAddress($address);
        if ($position instanceof Position && $this->maps2Pid !== 0) {
            $mapService = GeneralUtility::makeInstance(MapService::class);
            $poiUid = $mapService->createNewPoiCollection(
                $this->maps2Pid,
                $position,
                [
                    'title' => $poiTitle,
                    'address' => $address,
                ],
            );
        }

        return $poiUid;
    }
}
