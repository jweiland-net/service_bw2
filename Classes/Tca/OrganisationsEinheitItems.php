<?php
namespace JWeiland\ServiceBw2\Tca;

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

use JWeiland\ServiceBw2\Domain\Repository\OrganisationsEinheitRepository;
use JWeiland\ServiceBw2\Service\TranslationService;
use TYPO3\CMS\Backend\Form\FormDataProvider\AbstractItemProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class OrganisationsEinheitItems
 *
 * @package JWeiland\ServiceBw2\Tca
 */
class OrganisationsEinheitItems
{
    /**
     * @var OrganisationsEinheitRepository
     */
    protected $organisationsEinheitRepository;

    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * OrganisationsEinheitItems constructor.
     */
    public function __construct()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->organisationsEinheitRepository = $objectManager->get(OrganisationsEinheitRepository::class);
        $this->translationService = $objectManager->get(TranslationService::class);
    }

    /**
     * Get items for select field
     *
     * @param array $processorParameters
     * @param AbstractItemProvider $itemProvider
     * @return void
     */
    public function getItems(array $processorParameters, AbstractItemProvider $itemProvider)
    {
        try {
            $records = $this->organisationsEinheitRepository->getAll();
        } catch (\Exception $e) {
            $processorParameters['items'] = ['Exception: ' . $e->getMessage(), 'exception'];
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
     * @return void
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
