<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Utility;

/*
* This file is part of the TYPO3 CMS project.
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

/**
 * Class ServiceBwUtility
 * Utility for general static methods
 *
 * @package JWeiland\ServiceBw2\Utility;
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
