<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Hook;

use TYPO3\CMS\Core\Registry;

/**
 * Hook into DataHandler and clear sys_registry entry to allow switching into another authentication
 */
class ClearCacheHook
{
    protected Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Removes the authentication information from sys_registry, if all caches will be cleared.
     * That way a new Bearer information can be stored.
     */
    public function clearCachePostProc(array $params): void
    {
        if (($params['cacheCmd'] ?? '') === 'all') {
            $this->registry->remove('ServiceBw', 'token');
        }
    }
}
