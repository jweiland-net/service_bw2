<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Utility;

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

use JWeiland\ServiceBw2\Tca\OrganisationseinheitenItems;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Utility to be used in TCA files
 *
 * This utility will simplify the way how to configure TCA fields for service_bw2 items in other extensions.
 * @api feel free to use this utility on your own extension that depends on service_bw2
 */
class TCAUtility
{
    /**
     * Gets the TCA configuration for a field handling Organisationseinheiten items.
     * example TCA:
     * [
     *   'label' => 'I need a translation',
     *   'config' => \JWeiland\ServiceBw2\Utility\TCAUtility::getOrganisationseinheitenFieldTCAConfig()
     * ]
     * if you want to override or add your own settings:
     * [
     *   'label' => 'I steel need a translation',
     *   'config' => \JWeiland\ServiceBw2\Utility\TCAUtility::getOrganisationseinheitenFieldTCAConfig(['maxitems' => 1])
     * ]
     *
     * @param array $customSettingOverride Override or add your own settings to field config
     * @return array
     */
    public static function getOrganisationseinheitenFieldTCAConfig(array $customSettingOverride = []): array
    {
        $fieldTcaConfig = [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'itemsProcFunc' => OrganisationseinheitenItems::class . '->getItems',
            'enableMultiSelectFilterTextfield' => true,
            'default' => 0
        ];
        ArrayUtility::mergeRecursiveWithOverrule($fieldTcaConfig, $customSettingOverride);
        return $fieldTcaConfig;
    }
}
