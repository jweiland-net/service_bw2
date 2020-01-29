<?php
declare(strict_types = 1);
namespace JWeiland\ServiceBw2\Hooks;

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
    public function clearCachePostProc(array $params)
    {
        if ($params['cacheCmd'] === 'all') {
            $registry = GeneralUtility::makeInstance(Registry::class);
            $registry->remove('ServiceBw', 'token');
        }
    }
}
