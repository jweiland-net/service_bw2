<?php
declare(strict_types = 1);
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

use JWeiland\ServiceBw2\Request\Lebenslagen\Children;
use JWeiland\ServiceBw2\Request\Lebenslagen\Id;
use JWeiland\ServiceBw2\Request\Lebenslagen\Live;
use JWeiland\ServiceBw2\Request\Lebenslagen\References;
use JWeiland\ServiceBw2\Request\Lebenslagen\Roots;

/**
 * Class LebenslageRepository
 */
class LebenslagenRepository extends AbstractRepository
{
    /**
     * Gets all Lebenslagen roots
     *
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getRoots(): array
    {
        $request = $this->objectManager->get(Roots::class);
        $records = $this->serviceBwClient->processRequest($request);
        $this->translationService->translateRecords($records, true);

        return $records;
    }

    /**
     * Get all lebenslagen units from Service BW
     *
     * @return array
     * @throws \Exception if request is not valid!
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
     * Adds children recursive to $records
     * Will add children into $record[<id>]['_children'] = [];
     *
     * @param array $records
     * @throws \Exception if request is not valid!
     */
    protected function addChildrenToRecords(array &$records)
    {
        if (is_array($records)) {
            foreach ($records as &$record) {
                $children = $this->getChildren($record['id']);
                if (!empty($children)) {
                    $this->addChildrenToRecords($children);
                    $record['_children'] = $children;
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
     * Get related Verfahren for given Lebenslagen id
     *
     * @param int $id
     * @param string $type
     *
     * @return array
     * @throws \Exception
     */
    public function getReferences(int $id, string $type)
    {
        $request = $this->objectManager->get(References::class);

        $request->addParameter('id', $id);
        $request->addParameter('type', $type);

        $records = $this->serviceBwClient->processRequest($request);
        $this->translationService->translateRecords($records);

        return $records;
    }

    /**
     * Get a Lebenslage object by id
     * This is the object without Beschreibungstext if you want the object for
     * detail view, use getLiveOrganisationsEinheitById()
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
     * Get a live Lebenslagen object by id
     *
     * @param int $id
     * @return array
     * @throws \Exception if request is not valid!
     */
    public function getLiveLebenslagen(int $id): array
    {
        $request = $this->objectManager->get(Live::class);
        $request->addParameter('id', $id);
        $request->addParameter('lang', $this->translationService->getLanguage());
        $record = $this->serviceBwClient->processRequest($request);
        $record = $record[$id];

        return $record;
    }
}
