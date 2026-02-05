<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tca;

use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Form\FormDataProvider\AbstractItemProvider;

/**
 * Class OrganisationseinheitenItems
 */
readonly class OrganisationseinheitenItems
{
    public function __construct(
        protected Organisationseinheiten $organisationseinheiten,
        protected LoggerInterface $logger,
    ) {}

    /**
     * Get items for a select field
     */
    public function getItems(array $processorParameters, AbstractItemProvider $itemProvider): void
    {
        try {
            $records = $this->organisationseinheiten->findOrganisationseinheitenbaum();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Could not get organisationseinheiten: ' . $exception->getMessage(),
                [
                    'exception' => $exception,
                    'extKey' => 'service_bw2',
                ],
            );
            return;
        }

        $this->createList($processorParameters['items'], $records);
    }

    /**
     * Create an item list
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
