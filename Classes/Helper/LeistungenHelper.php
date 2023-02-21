<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Helper;

use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
 * Helper methods for Leistung records
 *
 * @internal
 */
class LeistungenHelper
{
    private const CACHE_IDENTIFIER = 'leistung_%d';

    /**
     * @var FrontendInterface
     */
    private $cache;

    /**
     * @var Leistungen
     */
    private $leistungen;

    public function __construct(FrontendInterface $cache, Leistungen $leistungen)
    {
        $this->cache = $cache;
        $this->leistungen = $leistungen;
    }

    public function getAdditionalData(int $id, bool $fetchIfMissing = true): array
    {
        $identifier = sprintf(self::CACHE_IDENTIFIER, $id);
        if (!$this->cache->has($identifier)) {
            if (!$fetchIfMissing) {
                return [];
            }

            $this->leistungen->findById($id);

            return $this->getAdditionalData($id, false);
        }

        return $this->cache->get($identifier);
    }

    /**
     * Used by LeistungenListener to save additional with Leistungen::findById() call
     */
    public function saveAdditionalData(int $id, array $data): void
    {
        $this->cache->set(
            sprintf(self::CACHE_IDENTIFIER, $id),
            $data,
            ['leistung_additionaldata'],
            604800
        );
    }
}
