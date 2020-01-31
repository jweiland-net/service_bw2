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
