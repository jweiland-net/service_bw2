<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\Configuration;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Mock to add a mocked instance of ExtensionConfiguration to use
 * ExtensionConfiguration::get() without a LocalConfiguration.
 *
 * This will always return an empty configuration for all extensions!
 */
trait ExtensionConfigurationMockTrait
{
    protected function addExtensionConfigurationMockToGeneralUtilityInstances(): void
    {
        $extensionConfiguration = $this->createMock(ExtensionConfiguration::class);
        $extensionConfiguration->method('get')->willReturn([]);
        GeneralUtility::addInstance(ExtensionConfiguration::class, $extensionConfiguration);
    }
}
