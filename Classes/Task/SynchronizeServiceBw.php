<?php

namespace JWeiland\ServiceBw2\Task;

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
use JWeiland\ServiceBw2\Domain\Model\KontaktPerson;
use JWeiland\ServiceBw2\Domain\Model\OrganisationsEinheit;
use JWeiland\ServiceBw2\Domain\Repository\ContactPersonRepository;
use JWeiland\ServiceBw2\Domain\Repository\KeywordRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationsEinheitRepository;
use JWeiland\ServiceBw2\Domain\Repository\ServiceRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use JWeiland\ServiceBw2\Request;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SynchronizeServiceBw extends AbstractTask
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var ServiceBwClient
     */
    protected $client;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Value from AdditionalFieldProvider
     * Value for storage PID
     *
     * @var int
     */
    public $pid = 0;

    /**
     * First language in this list is default language
     *
     * @var array
     */
    protected $languages = [
        'de' => 0,
        'en' => 1,
        'fr' => 2
    ];

    /**
     * constructor of this class.
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->registry = $this->objectManager->get(Registry::class);
        parent::__construct();
    }

    /**
     * This is the main method that is called when a task is executed
     * Note that there is no error handling, errors and failures are expected
     * to be handled and logged by the client implementations.
     * Should return TRUE on successful execution, FALSE on error.
     *
     * @return bool Returns TRUE on successful execution, FALSE on error
     */
    public function execute()
    {
        $this->synchronizeOrganizationalUnits();
        // $this->synchronizeContactPersons();
        // $this->synchronizeServices();
        // $this->synchronizeKeywords();

        return true;
    }

    /**
     * Synchronize organizational units
     *
     * @return void
     */
    protected function synchronizeOrganizationalUnits()
    {
        /** @var PersistenceManager $persistenceManager */
        $persistenceManager = $this->objectManager->get(PersistenceManager::class);

        /** @var OrganisationsEinheitRepository $organisationsEinheitRepository */
        $organisationsEinheitRepository = $this->objectManager->get(OrganisationsEinheitRepository::class);
        $organisationsEinheiten = $organisationsEinheitRepository->getAll();

        $defaultLanguage = key($organisationsEinheiten);
        foreach ($this->languages as $language => $sysLanguageUid) {
            if (empty($organisationsEinheiten[$language])) {
                // Languages from ExtConf could not be read. Don't save anything
                continue;
            }
            /** @var OrganisationsEinheit $organisationsEinheit */
            foreach ($organisationsEinheiten[$language] as $id => $organisationsEinheit) {
                if ($language !== $defaultLanguage) {
                    /** @var AbstractDomainObject $objectInDefaultLanguage */
                    $objectInDefaultLanguage = $organisationsEinheiten[$defaultLanguage][$id];
                    $organisationsEinheit->_setProperty('_localizedUid', $objectInDefaultLanguage->getUid());
                }
                $persistenceManager->add($organisationsEinheit);
            }
            $persistenceManager->persistAll();
            $persistenceManager->clearState();
        }
    }

    /**
     * Synchronize contact persons
     *
     * @return void
     */
    protected function synchronizeContactPersons()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tx_servicebw2_domain_model_contactperson');

        /** @var ContactPersonRepository $contactPersonRepository */
        $contactPersonRepository = $this->objectManager->get(ContactPersonRepository::class);
        $contactPersons = $contactPersonRepository->getAll();

        foreach ($contactPersons as $contactPerson) {
            $found = (bool)$connection->count(
                '*',
                'tx_servicebw2_domain_model_contactperson',
                ['id' => (int)$contactPerson['id']]
            );
            $contactPerson['pid'] = $this->pid;
            if ($found) {
                $connection->update(
                    'tx_servicebw2_domain_model_contactperson',
                    $contactPerson,
                    ['id' => $contactPerson['id']]
                );
            } else {
                $connection->insert(
                    'tx_servicebw2_domain_model_contactperson',
                    $contactPerson
                );
            }
        }
    }

    /**
     * Synchronize services
     *
     * @return void
     */
    protected function synchronizeServices()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tx_servicebw2_domain_model_service');

        /** @var ServiceRepository $serviceRepository */
        $serviceRepository = $this->objectManager->get(ServiceRepository::class);
        $services = $serviceRepository->getAll();

        foreach ($services as $service) {
            $found = (bool)$connection->count(
                '*',
                'tx_servicebw2_domain_model_service',
                ['id' => (int)$service['id']]
            );
            $service['pid'] = $this->pid;
            if ($found) {
                $connection->update(
                    'tx_servicebw2_domain_model_service',
                    $service,
                    ['id' => $service['id']]
                );
            } else {
                $connection->insert(
                    'tx_servicebw2_domain_model_service',
                    $service
                );
            }
        }
    }

    /**
     * Synchronize keywords
     *
     * @return void
     */
    protected function synchronizeKeywords()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tx_servicebw2_domain_model_keyword');

        /** @var KeywordRepository $keywordRepository */
        $keywordRepository = $this->objectManager->get(KeywordRepository::class);
        $keywords = $keywordRepository->getAll();

        foreach ($keywords as $keyword) {
            $found = (bool)$connection->count(
                '*',
                'tx_servicebw2_domain_model_keyword',
                ['id' => (int)$keyword['id']]
            );
            $keyword['pid'] = $this->pid;
            if ($found) {
                $connection->update(
                    'tx_servicebw2_domain_model_keyword',
                    $keyword,
                    ['id' => $keyword['id']]
                );
            } else {
                $connection->insert(
                    'tx_servicebw2_domain_model_keyword',
                    $keyword
                );
            }
        }
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string Information to display
     */
    public function getAdditionalInformation()
    {
        $content = 'nothing jet';
        return $content;
    }

    /**
     * This method is used to add a message to the internal queue
     *
     * @param string $message The message itself
     * @param int $severity Message level (according to FlashMessage class constants)
     *
     * @return void
     */
    public function addMessage($message, $severity = FlashMessage::OK) {
        /** @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, '', $severity);
        /** @var FlashMessageService $flashMessageService */
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        /** @var FlashMessageQueue $defaultFlashMessageQueue */
        $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $defaultFlashMessageQueue->enqueue($flashMessage);
    }

    /**
     * This object will be serialized in tx_scheduler_task.
     * While executing this task, it seems that __construct will not be called again and
     * all properties will be reconstructed by the information in serialized value.
     * These properties will be created again with new() instead of GeneralUtility::makeInstance()
     * which leads to the problem, that object of type SingletonInterface were created twice.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->registry = $this->objectManager->get(Registry::class);
    }
}
