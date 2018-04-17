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
 * PostProcessor to check if the response array contains
 * links that has been defined as "unused" or "broken".
 * This PostProcessor removes those links from array.
 *
 * @package JWeiland\ServiceBw2\PostProcessor;
 */
class LinkStatusPostProcessor extends AbstractPostProcessor
{
    /**
     * Check for unused or broken links
     *
     * @param array $response
     * @return array
     */
    public function process($response)
    {
        if (is_array($response)) {
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
        }
        return $response;
    }
}