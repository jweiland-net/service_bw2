<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tca;

use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Form\FormDataProvider\AbstractItemProvider;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class OrganisationseinheitenItems
 */
class OrganisationseinheitenItems implements SingletonInterface
{
    protected Organisationseinheiten $organisationseinheiten;

    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->organisationseinheiten = GeneralUtility::makeInstance(Organisationseinheiten::class);
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }

    /**
     * Get items for select field
     */
    public function getItems(array $processorParameters, AbstractItemProvider $itemProvider): void
    {
        try {
            $records = $this->organisationseinheiten->findOrganisationseinheitenbaum();
        } catch (\Exception $e) {
            $this->logger->error(
                'Could not get organisationseinheiten: ' . $e->getMessage(),
                [
                    'exception' => $e,
                    'extKey' => 'service_bw2'
                ]
            );
            return;
        }

        $this->createList($processorParameters['items'], $records);
    }

    /**
     * Create item list
     */
    protected function createList(array &$items, array $records): void
    {
        foreach ($records as $record) {
            $items[] = [$record['name'], $record['id']];
            if ($record['untergeordneteOrganisationseinheiten']) {
                $this->createList($items, $record['untergeordneteOrganisationseinheiten']);
            }
        }
    }
}
