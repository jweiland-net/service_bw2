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

use JWeiland\ServiceBw2\Request\Leistungen\Leistungen;
use JWeiland\ServiceBw2\Request\Leistungen\Live;
use JWeiland\ServiceBw2\Request\Zustaendigkeiten\Organisationseinheit;

/**
 * Class LeistungenRepository
 */
class LeistungenRepository extends AbstractRepository
{
    const SORT_DIRECTION = 'asc';

    /**
     * Get all Leistungen
     *
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getAll(): array
    {
        $records = [];
        $page = 0;
        $pageSize = 1000;
        do {
            $request = $this->objectManager->get(Leistungen::class);
            $request->addParameter('page', $page);
            $request->addParameter('pageSize', $pageSize);
            $request->addParameter('sortProperty', 'displayName');
            $request->addParameter('sortDirection', self::SORT_DIRECTION);
            $records += $this->serviceBwClient->processRequest($request);
            $itemsLeft = $records['_root']['total'] - ($pageSize * ($page + 1));
            $page++;
            unset($records['_root']);
        } while ($itemsLeft > 0);
        return $records;
    }

    /**
     * Get Leistungen that are related to Organisationseinheiten ($id)
     *
     * @param int $id of the Organisationseinheiten
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getByOrganisationseinheit(int $id): array
    {
        $records = [];
        $page = 0;
        $pageSize = 1000;
        do {
            $request = $this->objectManager->get(Organisationseinheit::class);
            $request->addParameter('organisationseinheitId', $id);
            $request->addParameter('page', $page);
            $request->addParameter('pageSize', $pageSize);
            $request->addParameter('sortProperty', 'leistung.displayName');
            $request->addParameter('sortDirection', self::SORT_DIRECTION);
            $records += $this->serviceBwClient->processRequest($request);
            $itemsLeft = $records['_root']['total'] - ($pageSize * ($page + 1));
            $page++;
            unset($records['_root']);
        } while ($itemsLeft > 0);
        return $records;
    }

    /**
     * Get live Leistung by id
     * this request contains sections (descriptions)
     *
     * @param int $id of Leistung
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getLiveById(int $id): array
    {
        $request = $this->objectManager->get(Live::class);
        $request->addParameter('id', $id);
        $request->addParameter('lang', $this->translationService->getLanguage());
        $record = $this->serviceBwClient->processRequest($request);
        $record = $record[$id];
        return $record;
    }
}
