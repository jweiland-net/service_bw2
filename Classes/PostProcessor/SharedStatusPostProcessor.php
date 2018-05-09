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
 * PostProcessor to check if items are allowed to be displayed
 * e.g. to check if forms are allowed to be displayed
 */
class SharedStatusPostProcessor extends AbstractPostProcessor
{
    /**
     * Check if given items are allowed to be displayed
     *
     * @param array $response
     * @return array
     */
    public function process($response): array
    {
        $response = (array)$response;
        foreach ($response as $key => $item) {
            if (array_key_exists('shared', $item)) {
                // Remove unshared items
                if ($item['shared'] === false || $item['shared'] === 'false') {
                    unset($response[$key]);
                }
            }
        }
        return $response;
    }
}
