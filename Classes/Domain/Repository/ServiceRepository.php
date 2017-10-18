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
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use JWeiland\ServiceBw2\Request;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ServiceRepository extends Repository
{
    /**
     * @var ServiceBwClient
     */
    protected $serviceBwClient;

    /**
     * inject serviceBwClient
     *
     * @param ServiceBwClient $serviceBwClient
     *
     * @return void
     */
    public function injectServiceBwClient(ServiceBwClient $serviceBwClient)
    {
        $this->serviceBwClient = $serviceBwClient;
    }

    /**
     * Get all organizational units from Service BW
     *
     * @return array
     */
    public function getAll()
    {
        /** @var Request\Services\ServicesList $request */
        $request = $this->objectManager->get(Request\Services\ServicesList::class);
        $services = $this->serviceBwClient->processRequest($request);

        return $services;
    }
}
