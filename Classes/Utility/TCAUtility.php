<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Utility;

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
            'default' => 0
        ];
        ArrayUtility::mergeRecursiveWithOverrule($fieldTcaConfig, $customSettingOverride);
        return $fieldTcaConfig;
    }
}
