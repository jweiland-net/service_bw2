<?php
declare(strict_types=1);
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
            // Remove non published items from array
            if (!array_key_exists('publishStatus', $item) || $item['publishStatus'] !== 'DONE') {
                unset($response[$key]);
            }
        }
        return $response;
    }
}
