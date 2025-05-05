<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Utility;

use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This utility can be used inside domain models.
 * @api
 */
class ModelUtility
{
    /**
     * Returns an array with organisationseinheiten
     * This can be used in the getter of your extensions model e.g.
     * Important: define as string, so property mapper will fill that property with the ids e.g. 1245,565
     * protected $organisationseinheiten = '';
     * public function getOrganisationseinheiten(): array
     * {
     *     return $this->organisationseinheiten = ModelUtility::getOrganisationseinheiten($this->organisationseinheiten);
     * }
     *
     * @param array|int|string $organisationseinheiten comma separated list to get multiple records,
     *                         int to get one record, array will be returned as it is
     * @return array where key is the id of the record and the value is an array
     *         with fields of that organisationseinheit
     * @throws \JsonException
     */
    public static function getOrganisationseinheiten(array|int|string $organisationseinheiten): array
    {
        if (!is_array($organisationseinheiten)) {
            $ids = \json_decode('[' . $organisationseinheiten . ']', false, 512, JSON_THROW_ON_ERROR);
            $organisationseinheiten = [];
            $requestClass = GeneralUtility::makeInstance(Organisationseinheiten::class);
            foreach ($ids as $id) {
                try {
                    $record = $requestClass->findById((int)$id);
                } catch (\Exception $exception) {
                    $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
                    $logger->error(
                        'Exception in ModelUtility while executing findById() with id' . $id,
                        [
                            'exception' => $exception,
                            'extKey' => 'service_bw2',
                        ],
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
     * @param int $organisationseinheitUid UID of a single organisationseinheit
     * @return array of organisationseinheit e.g. ['id' => 1234, ...]
     */
    public static function getOrganisationseinheit(int $organisationseinheitUid): array
    {
        $requestClass = GeneralUtility::makeInstance(Organisationseinheiten::class);
        try {
            $organisationseinheit = $requestClass->findById($organisationseinheitUid);
        } catch (\Exception $exception) {
            $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
            $logger->error(
                'Exception in ModelUtility while executing findById() with id' . $organisationseinheitUid,
                [
                    'exception' => $exception,
                    'extKey' => 'service_bw2',
                ],
            );
            $organisationseinheit = [];
        }

        return $organisationseinheit;
    }
}
