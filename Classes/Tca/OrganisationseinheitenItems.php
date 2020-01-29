<?php
namespace JWeiland\ServiceBw2\Tca;

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

use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Service\TranslationService;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Form\FormDataProvider\AbstractItemProvider;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class OrganisationseinheitenItems
 */
class OrganisationseinheitenItems implements SingletonInterface
{
    /**
     * @var OrganisationseinheitenRepository
     */
    protected $organisationseinheitenRepository;

    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * OrganisationseinheitenItems constructor.
     */
    public function __construct()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->organisationseinheitenRepository = $objectManager->get(OrganisationseinheitenRepository::class);
        $this->translationService = $objectManager->get(TranslationService::class);
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }

    /**
     * Get items for select field
     *
     * @param array $processorParameters
     * @param AbstractItemProvider $itemProvider
     */
    public function getItems(array $processorParameters, AbstractItemProvider $itemProvider)
    {
        try {
            $records = $this->organisationseinheitenRepository->getAll();
        } catch (\Exception $e) {
            $processorParameters['items'] = ['Exception: ' . $e->getMessage(), 'exception'];
            $this->logger->error(
                'Could not get organisationseinheiten: ' . $e->getMessage(),
                [
                    'extKey' => 'service_bw2'
                ]
            );
            return;
        }
        $this->translationService->translateRecords($records, true);
        $this->createList($processorParameters['items'], $records);
    }

    /**
     * Create item list
     *
     * @param array $items
     * @param array $records
     */
    protected function createList(array &$items, array $records)
    {
        foreach ($records as $record) {
            $items[] = [$record['name'], $record['id']];
            if (array_key_exists('_children', $record)) {
                $this->createList($items, $record['_children']);
            }
        }
    }
}
