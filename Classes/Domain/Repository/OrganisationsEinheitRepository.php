<?php
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

use JWeiland\ServiceBw2\Request;
use JWeiland\ServiceBw2\Service\TranslationService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrganisationsEinheitRepository extends AbstractRepository
{
    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * inject translationService
     *
     * @param TranslationService $translationService
     * @return void
     */
    public function injectTranslationService(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Get all organizational units from Service BW
     * Will return an associative array including OrganisationsEinheit instances
     * Children of OrganisationsEinheit instances are located inside _children of current instance
     * e.g. $records[12345]['_children'] = [...]
     * Those OrganisationsEinheit instances doesn´t contain all fields! Take a look at the service_bw
     * API documentation
     *
     * @return array
     */
    public function getAll()
    {
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Roots::class);
        $records = $this->serviceBwClient->processRequest($request);
        $this->addChildrenToRecords($records);
        $this->translationService->translateRecords($records, true);

        return $records;
    }

    /**
     * Adds children recursive to $records
     * Will add children into $record[<id>]['_children'] = [];
     *
     * Children are from type OrganisationsEinheit BUT doesn´t contain
     * all fields! Take a look at service_bw API documentation if you
     * want to know which fields are provided.
     *
     * @param array $records
     * @return void
     */
    protected function addChildrenToRecords(array &$records)
    {
        if (is_array($records)) {
            foreach ($records as &$organisationsEinheit) {
                $children = $this->getChildren($organisationsEinheit['id']);
                if (!empty($children)) {
                    $this->addChildrenToRecords($children);
                    $organisationsEinheit['_children'] = $children;
                }
            }
        }
    }

    /**
     * Get children records of ID
     *
     * @param int $id
     *
     * @return array
     */
    public function getChildren(int $id): array
    {
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Children::class);
        $request->addParameter('id', $id);
        return $this->serviceBwClient->processRequest($request);
    }

    /**
     * Get a OrganisationsEinheit by id
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Live::class);
        $request->addParameter('id', $id);
        $request->addParameter('lang', $this->translationService->getLanguage());
        $record = $this->serviceBwClient->processRequest($request);
        $record = $record[$id];
        return $record;
    }
}
