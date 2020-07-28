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
 * PostProcessor to check the publishStatus of items.
 * Items without a publishStatus or where publishStatus
 * does not equal DONE will be removed from $response array
 */
class PublishStatusPostProcessor extends AbstractPostProcessor
{
    /**
     * Remove items without publishStatus DONE
     *
     * @param mixed $response
     * @return array
     */
    public function process($response): array
    {
        $response = (array)$response;
        foreach ($response as $key => $item) {
            // Skip _root entry
            if ($key === '_root') {
                continue;
            }

            // Check if leistung is a leistung or a zustaendigkeit with a leistung as array item
            if (array_key_exists('leistung', $item) && is_array($item['leistung'])) {
                $leistung = $item['leistung'];
            } else {
                $leistung = $item;
            }

            // Remove non published items from array
            if (!array_key_exists('publishStatus', $leistung) || strtoupper($leistung['publishStatus']) !== 'DONE') {
                unset($response[$key]);
            }
        }
        return $response;
    }
}
