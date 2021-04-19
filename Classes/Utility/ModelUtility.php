<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Utility;

use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * This utility can be used inside domain models.
 * @api
 */
class ModelUtility
{
    /**
     * Returns an array with organisationseinheiten
     * This can be used in the getter of your extensions model e.g.
     *
     * Important: define as string, so property mapper will fill that property with the ids e.g. 1245,565
     * protected $organisationseinheiten = '';
     *
     * public function getOrganisationseinheiten(): array
     * {
     *     return $this->organisationseinheiten = ModelUtility::getOrganisationseinheiten($this->organisationseinheiten);
     * }
     *
     * @param string|int|array comma separated list to get multiple records,
     *                         int to get one record, array will be returned as it is
     * @return array where key is the id of the record and the value is an array
     *         with fields of that organisationseinheit
     */
    public static function getOrganisationseinheiten($organisationseinheiten): array
    {
        if (!is_array($organisationseinheiten)) {
            $ids = \json_decode('[' . $organisationseinheiten . ']');
            $organisationseinheiten = [];
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $requestClass = $objectManager->get(Organisationseinheiten::class);
            foreach ($ids as $id) {
                try {
                    $record = $requestClass->findById((int)$id);
                } catch (\Exception $exception) {
                    $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
                    $logger->error(
                        'Exception in ModelUtility while executing findById() with id' . $id,
                        [
                            'exception' => $exception,
                            'extKey' => 'service_bw2'
                        ]
                    );
                    continue;
                }
                $organisationseinheiten[$id] = $record;
            }
        }
        return $organisationseinheiten;
    }

    /**
     * Returns an array with fields of a organisationseinheit
     * This can be used in the getter of your extensions model e.g.
     *
     * Important: define as string or int, so property mapper will fill that property with the ids e.g. 1245
     * protected $organisationseinheit = '';
     *
     * public function getOrganisationseinheit(): array
     * {
     *     return $this->organisationseinheit = ModelUtility::getOrganisationseinheit($this->organisationseinheit);
     * }
     *
     * @param string|int id of a single organisationseinheit
     * @return array of organisationseinheit e.g. ['id' => 1234, ...]
     */
    public static function getOrganisationseinheit($organisationseinheit): array
    {
        if (!is_array($organisationseinheit)) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $requestClass = $objectManager->get(Organisationseinheiten::class);
            try {
                $organisationseinheit = $requestClass->findById((int)$organisationseinheit);
            } catch (\Exception $exception) {
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
                $logger->error(
                    'Exception in ModelUtility while executing findById() with id' . $organisationseinheit,
                    [
                        'exception' => $exception,
                        'extKey' => 'service_bw2'
                    ]
                );
                $organisationseinheit = [];
            }
        }
        return $organisationseinheit;
    }
}
