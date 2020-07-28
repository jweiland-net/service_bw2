<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Utility;

/**
 * Class ServiceBwUtility
 * Utility for general static methods
 */
class ServiceBwUtility
{
    /**
     * Remove keys $keys from array $array
     *
     * @param array $array ['foo' => 'bar', 'test' => 'item']
     * @param array $keys ['foo']
     * @return array
     */
    public static function removeItemsFromArray(array $array, array $keys): array
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
