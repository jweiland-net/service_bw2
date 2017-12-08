<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Domain\Repository;

/*
 * This file is part of the service_bw2 project.
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

use JWeiland\Maps2\Utility\GeocodeUtility;
use JWeiland\ServiceBw2\Request;
use JWeiland\ServiceBw2\Request\Organisationseinheiten\Children;
use JWeiland\ServiceBw2\Request\Organisationseinheiten\Id;
use JWeiland\ServiceBw2\Request\Organisationseinheiten\Live;
use JWeiland\ServiceBw2\Request\Organisationseinheiten\Roots;
use JWeiland\ServiceBw2\Service\TranslationService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrganisationseinheitenRepository extends AbstractRepository
{
    /**
     * Get all organizational units from Service BW
     *
     * Will return an associative array including Organisationseinheiten instances
     * Children of Organisationseinheiten instances are located inside _children of current instance
     * e.g. $records[12345]['_children'] = [...]
     *
     * Those Organisationseinheiten instances doesn´t contain all fields! Take a look at the service_bw
     * API documentation
     *
     * @return array
     * @throws \Exception if request if not valid!
     */
    public function getAll(): array
    {
        $request = $this->objectManager->get(Roots::class);
        $records = $this->serviceBwClient->processRequest($request);
        $this->addChildrenToRecords($records);
        $this->translationService->translateRecords($records, true);

        return $records;
    }

    /**
     * Get records and children of that records by passing one or multiple $ids
     *
     * Will return an associative array including Organisationseinheiten instances
     * Children of Organisationseinheiten instances are located inside _children of current instance
     * e.g. $records[12345]['_children'] = [...]
     *
     * Those Organisationseinheiten instances doesn´t contain all fields! Take a look at the service_bw
     * API documentation
     *
     * @param array $ids e.g. [42, 56] or [32]
     * @return array records with children
     * @throws \Exception if request is not valid!
     */
    public function getRecordsWithChildren(array $ids): array
    {
        $records = [];
        foreach ($ids as $id) {
            $records[] = $this->getById($id);
        }
        $this->addChildrenToRecords($records);
        $this->translationService->translateRecords($records, true);
        return $records;
    }

    /**
     * Adds children recursive to $records
     * Will add children into $record[<id>]['_children'] = [];
     *
     * Children are from type Organisationseinheiten BUT doesn´t contain
     * all fields! Take a look at service_bw API documentation if you
     * want to know which fields are provided.
     *
     * @param array $records
     * @return void
     * @throws \Exception if request is not valid!
     */
    protected function addChildrenToRecords(array &$records)
    {
        if (is_array($records)) {
            foreach ($records as &$organisationseinheit) {
                $children = $this->getChildren($organisationseinheit['id']);
                if (!empty($children)) {
                    $this->addChildrenToRecords($children);
                    $organisationseinheit['_children'] = $children;
                }
            }
        }
    }

    /**
     * Get children records of ID
     *
     * @param int $id
     * @return array
     * @throws \Exception if request is not valid!
     */
    public function getChildren(int $id): array
    {
        $request = $this->objectManager->get(Children::class);
        $request->addParameter('id', $id);
        return $this->serviceBwClient->processRequest($request);
    }

    /**
     * Get a Organisationseinheiten object by id
     * This is the object without Beschreibungstext if you want the object for
     * detail view, use getLiveOrganisationseinheitById()
     *
     * @param int $id
     * @param bool $removeParents set false if you want to get parent objects that
     *                            are provided by api (longer loading time)
     * @return array
     * @throws \Exception if request is not valid!
     */
    public function getById(int $id, bool $removeParents = true): array
    {
        $request = $this->objectManager->get(Id::class);
        $request->addParameter('id', $id);
        $record = $this->serviceBwClient->processRequest($request);
        if ($removeParents) {
            unset($record[$id]['uebergeordnet']);
        }
        $this->translationService->translateRecords($record, false, true);
        $record = $record[$id];
        return $record;
    }

    /**
     * Get a live Organisationseinheiten object by id
     *
     * @param int $id
     * @return array
     * @throws \Exception if request is not valid!
     */
    public function getLiveOrganisationseinheitById(int $id): array
    {
        $request = $this->objectManager->get(Live::class);
        $request->addParameter('id', $id);
        $request->addParameter('lang', $this->translationService->getLanguage());
        $record = $this->serviceBwClient->processRequest($request);
        $record = $record[$id];
        return $record;
    }
}
