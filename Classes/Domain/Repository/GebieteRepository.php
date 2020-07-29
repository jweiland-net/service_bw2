<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Request\Gebiete\IdsByAgs;
use JWeiland\ServiceBw2\Request\Gebiete\IdsByPlz;

/**
 * Class GebieteRepository
 */
class GebieteRepository extends AbstractRepository
{
    /**
     * Get all region IDs by AGS (Amtlicher Gemeindeschluessel)
     *
     * @param int $ags
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getRegionIdsByAgs(int $ags): array
    {
        $agsRequest = $this->objectManager->get(IdsByAgs::class);
        $agsRequest->addParameter('ags', $ags);
        return $this->serviceBwClient->processRequest($agsRequest);
    }

    /**
     * Get all region IDs by PLZ
     *
     * @param string $plz
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getRegionIdsByPlz(string $plz): array
    {
        $plzRequest = $this->objectManager->get(IdsByPlz::class);
        $plzRequest->addParameter('plz', $plz);
        return $this->serviceBwClient->processRequest($plzRequest);
    }
}
