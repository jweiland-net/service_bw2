<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\PostProcessor;

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
 * Class RenameArrayKeyPostProcessor
 *
 * @package JWeiland\ServiceBw2\PostProcessor;
 */
class RenameArrayKeyPostProcessor extends AbstractPostProcessor
{
    /**
     * Post process array response
     * Will return an array with items from response where the array key
     * equals the item id or if no item id isset an key like unknown_id_<n>
     *
     * @param array $response after JsonPostProcessor
     * @return array
     */
    public function process($response): array
    {
        $itemsById = [];
        $noId = 0;
        foreach ($response as $item) {
            if (array_key_exists('id', $item)) {
                $itemsById[$item['id']] = $item;
            } else {
                $itemsById['unknown_id_' . $noId] = $item;
                $noId++;
            }
        }
        return $itemsById;
    }
}
