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

use TYPO3\CMS\Core\Utility\MathUtility;

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
        $response = $this->sanitizeRecords($response);
        $itemsById = [];
        $noId = 0;
        foreach ($response as $key => $item) {
            // only process items that are not empty and does not begin with '_' (underline)
            if (!empty($item) && $key[0] !== '_') {
                if (array_key_exists('id', $item)) {
                    $itemsById[$item['id']] = $item;
                } else {
                    $itemsById['unknown_id_' . $noId] = $item;
                    $noId++;
                }
            } elseif($key[0] === '_') {
                // if array key begins with '_' (underline), we´ll add it without modifying
                // the array key
                $itemsById[$key] = $item;
            }
        }
        return $itemsById;
    }

    /**
     * Sanitize records
     *
     * @param array $records
     *
     * @return array
     *
     * @see: allValuesAreArrays
     */
    protected function sanitizeRecords(array $records): array
    {
        return $this->allValuesAreArrays($records) ? $records: [$records];
    }

    /**
     * $this->translate can only work with following arrays
     * 0 => [id => 1]
     * 1 => [id => 3]
     * 2 => [id => 5]
     *
     * if we get something like:
     * id => 123
     * title => Hello
     * name => Stefan
     * this method will return false
     *
     * @param array $records
     *
     * @return bool
     */
    protected function allValuesAreArrays(array $records): bool
    {
        return MathUtility::canBeInterpretedAsInteger(key($records));
    }
}
