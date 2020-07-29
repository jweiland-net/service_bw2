<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

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
