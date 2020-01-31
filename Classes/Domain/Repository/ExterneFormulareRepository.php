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

use JWeiland\ServiceBw2\Request\ExterneFormulare\ListByLeistungAndRegion;

/**
 * Class ExterneFormulareRepository
 */
class ExterneFormulareRepository extends AbstractRepository
{
    /**
     * Get Formulare by Leistung and Region
     *
     * @param int $leistungId of Leistung
     * @param array $regionIds Region IDs (e.g. [123,456,789])
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getByLeistungAndRegion(int $leistungId, array $regionIds): array
    {
        $request = $this->objectManager->get(ListByLeistungAndRegion::class);
        $request->addParameter('leistungId', $leistungId);
        $request->setRegionIds($regionIds);
        $records = $this->serviceBwClient->processRequest($request);
        $this->translationService->translateRecords($records);
        return $records;
    }
}
