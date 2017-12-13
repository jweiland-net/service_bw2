<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Utility;

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

use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * This utility can be used inside domain models.
 *
 * @package JWeiland\ServiceBw2\Utility;
 * @api
 */
class ModelUtility
{
    /**
     * Returns an array with organisationseinheiten
     * This can be used in the getter of your extensions model e.g.
     *
     * public function getOrganisationseinheiten(): array
     * {
     *     return ModelUtility::getOrganisationseinheiten($this->organisationseinheiten);
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
            $organisationseinheitenRepository = $objectManager->get(OrganisationseinheitenRepository::class);
            foreach ($ids as $id) {
                try {
                    $record = $organisationseinheitenRepository->getById((int)$id);
                } catch (\Exception $exception) {
                    GeneralUtility::sysLog(
                        'Exception in OrganisationseinheitenTypeConverter while executing getById() with id' . $id
                        . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ' Code: '
                        . $exception->getCode(),
                        'service_bw2',
                        GeneralUtility::SYSLOG_SEVERITY_ERROR
                    );
                    continue;
                }
                $organisationseinheiten += $record;
            }
        }
        return $organisationseinheiten;
    }
}
