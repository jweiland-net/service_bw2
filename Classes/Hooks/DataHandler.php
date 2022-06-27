<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Hooks;

use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into DataHandler and clear sys_registry entry to allow switching into another Authentication
 */
class DataHandler
{
    /**
     * Removes the Authentication information from sys_registry, if all caches will be cleared.
     * That way a new Bearer information can be stored.
     *
     * @param array $params
     */
    public function clearCachePostProc(array $params): void
    {
        if ($params['cacheCmd'] === 'all') {
            $registry = GeneralUtility::makeInstance(Registry::class);
            $registry->remove('ServiceBw', 'token');
        }
    }
}
