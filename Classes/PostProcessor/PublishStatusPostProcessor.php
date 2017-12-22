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
 * PostProcessor to check the publishStatus of items.
 * Unpublished items (publishStatus: NONE) will be removed
 * from $response array
 *
 * @package JWeiland\ServiceBw2\PostProcessor;
 */
class PublishStatusPostProcessor extends AbstractPostProcessor
{
    /**
     * Check for unpublished entries
     *
     * @param array $response
     * @return array
     */
    public function process($response)
    {
        if (is_array($response)) {
            foreach ($response as $key => $item) {
                // Remove non published items from array
                if (array_key_exists('publishStatus', $item) && $item['publishStatus'] === 'NONE') {
                    unset($response[$key]);
                }
            }
        }
        return $response;
    }
}
