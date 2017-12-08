<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Domain\Repository;

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

use JWeiland\ServiceBw2\Request\Leistungen\Live;
use JWeiland\ServiceBw2\Request\Zustaendigkeiten\Organisationseinheit;

/**
 * Class LeistungenRepository
 *
 * @package JWeiland\ServiceBw2\Domain\Repository;
 */
class LeistungenRepository extends AbstractRepository
{
    /**
     * Get Leistungen that are related to Organisationseinheiten ($id)
     *
     * @param int $id of the Organisationseinheiten
     * @return array
     * @throws \Exception if request is not valid
     */
    public function getByOrganisationseinheit(int $id): array
    {
        $request = $this->objectManager->get(Organisationseinheit::class);
        $request->addParameter('organisationseinheitId', $id);
        return $this->serviceBwClient->processRequest($request);
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
