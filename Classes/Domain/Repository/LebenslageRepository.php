<?php
declare(strict_types = 1);
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

use JWeiland\ServiceBw2\Request\Lebenslagen\Roots;

/**
 * Class LebenslageRepository
 *
 * @package JWeiland\ServiceBw2\Domain\Repository
 */
class LebenslageRepository extends AbstractRepository
{
    /**
     * Get all lebenslagen units from Service BW
     *
     * @return array
     * @throws \Exception if request if not valid!
     */
    public function getAll(): array
    {
        $request = $this->objectManager->get(Roots::class);
        $records = $this->serviceBwClient->processRequest($request);
        //$this->addChildrenToRecords($records);
        $this->translationService->translateRecords($records, true);

        return $records;
    }
}
