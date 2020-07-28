<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\PostProcessor;

/**
 * PostProcessor to check if the response array contains
 * links that has been defined as "unused" or "broken".
 * This PostProcessor removes those links from array.
 */
class LinkStatusPostProcessor extends AbstractPostProcessor
{
    /**
     * Check for unused or broken links
     *
     * @param mixed $response
     * @return array
     */
    public function process($response): array
    {
        $response = (array)$response;
        foreach ($response as $key => $item) {
            // Remove unused or broken links
            if (
                !isset($item['unused'], $item['broken'])
                || $item['unused'] !== false
                || $item['broken'] !== false
            ) {
                unset($response[$key]);
            }
        }
        return $response;
    }
}
