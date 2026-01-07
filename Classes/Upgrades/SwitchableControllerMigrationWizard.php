<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Upgrades;

use Doctrine\DBAL\Exception;
use JWeiland\ServiceBw2\Traits\QueryBuilderTrait;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('serviceBw2_pluginUpgaterWizard')]
final class SwitchableControllerMigrationWizard implements UpgradeWizardInterface
{
    use QueryBuilderTrait;

    private const MIGRATION_SETTINGS = [
        [
            'switchableControllerActions' => 'Organisationseinheiten->list',
            'targetListType' => 'servicebw2_organizationalunitslist',
        ],
        [
            'switchableControllerActions' => 'Organisationseinheiten->show',
            'targetListType' => 'servicebw2_organizationalunitsshow',
        ],
        [
            'switchableControllerActions' => 'Leistungen->list',
            'targetListType' => 'servicebw2_serviceslist',
        ],
        [
            'switchableControllerActions' => 'Leistungen->show',
            'targetListType' => 'servicebw2_servicesshow',
        ],
        [
            'switchableControllerActions' => 'Lebenslagen->list',
            'targetListType' => 'servicebw2_lifesituationslist',
        ],
        [
            'switchableControllerActions' => 'Lebenslagen->show',
            'targetListType' => 'servicebw2_lifesituationsshow',
        ],
        [
            'switchableControllerActions' => 'Suche->list',
            'targetListType' => 'servicebw2_servicebw2search',
        ],
    ];

    protected FlexFormService $flexFormService;

    protected FlexFormTools $flexFormTools;

    public function __construct()
    {
        $this->flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $this->flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
    }

    public function getTitle(): string
    {
        return '[service_bw2] Migrate Switchable Controller Actions to Plugins';
    }

    public function getDescription(): string
    {
        $description = 'This extension, service_bw2, has undergone a restructuring process. The old plugin that utilized switchableControllerActions has been refactored into distinct, standalone plugins. ';
        $description .= 'This update wizard facilitates the seamless migration of all existing plugin configurations, ensuring a smooth transition to the new plugin structure. ';

        return $description . ('The update process will automatically adapt and apply the necessary changes. The total count of plugins affected by this migration is: ' . count(
            $this->getMigrationRecords(),
        ));
    }

    public function executeUpdate(): bool
    {
        $records = $this->getMigrationRecords();
        foreach ($records as $record) {
            $flexForm = $this->flexFormService->convertFlexFormContentToArray($record['pi_flexform']);
            $targetListType = $this->getTargetListType($flexForm['switchableControllerActions'] ?? '');

            if ($targetListType === '') {
                continue;
            }

            // Update record with migrated types (this is needed because FlexFormTools
            // looks up those values in the given record and assumes they're up-to-date)
            $record['CType'] = $targetListType;
            $record['list_type'] = '';

            // Clean up flexform
            $newFlexform = $this->flexFormTools->cleanFlexFormXML('tt_content', 'pi_flexform', $record);
            $flexFormData = GeneralUtility::xml2array($newFlexform);

            // Remove flexform data which do not exist in flexform of new plugin
            foreach ($flexFormData['data'] as $sheetKey => $sheetData) {
                // Remove empty sheets
                if (count($flexFormData['data'][$sheetKey]['lDEF']) === 0) {
                    unset($flexFormData['data'][$sheetKey]);
                }
            }

            $newFlexform = count($flexFormData['data']) > 0 ? $this->array2xml($flexFormData) : '';

            $this->updateContentElement($record['uid'], $targetListType, $newFlexform);
        }

        return true;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function updateNecessary(): bool
    {
        return $this->checkIfWizardIsRequired();
    }

    public function checkIfWizardIsRequired(): bool
    {
        return $this->getMigrationRecords() !== [];
    }

    protected function getMigrationRecords(): array
    {
        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        try {
            return $queryBuilder
                ->select('uid', 'pid', 'CType', 'list_type', 'pi_flexform')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'CType',
                        $queryBuilder->createNamedParameter('list'),
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('servicebw2_servicebw'),
                    ),
                )
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $exception) {
            return [];
        }
    }

    protected function getTargetListType(string $switchableControllerActions): string
    {
        foreach (self::MIGRATION_SETTINGS as $setting) {
            if ($setting['switchableControllerActions'] === $switchableControllerActions) {
                return $setting['targetListType'];
            }
        }

        return '';
    }

    protected function array2xml(array $input = []): string
    {
        $options = [
            'parentTagMap' => [
                'data' => 'sheet',
                'sheet' => 'language',
                'language' => 'field',
                'el' => 'field',
                'field' => 'value',
                'field:el' => 'el',
                'el:_IS_NUM' => 'section',
                'section' => 'itemType',
            ],
            'disableTypeAttrib' => 2,
        ];
        $spaceInd = 4;
        $output = GeneralUtility::array2xml($input, '', 0, 'T3FlexForms', $spaceInd, $options);
        return '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>' . LF . $output;
    }

    protected function updateContentElement(int $uid, string $newCtype, string $flexform): void
    {
        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $queryBuilder->update('tt_content')
            ->set('CType', $newCtype)
            ->set('list_type', '')
            ->set('pi_flexform', $flexform)
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT),
                ),
            )
            ->executeStatement();
    }
}
