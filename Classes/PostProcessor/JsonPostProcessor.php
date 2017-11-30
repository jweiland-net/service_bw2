<?php
namespace JWeiland\ServiceBw2\PostProcessor;

/*
 * This file is part of the service_bw2 project.
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
 * Class JsonPostProcessor
 *
 * @package JWeiland\ServiceBw2\PostProcessor
 */
class JsonPostProcessor extends AbstractPostProcessor
{
    /**
     * Post process json response
     *
     * @param string $response
     *
     * @return array|null
     */
    public function process($response)
    {
        $response = trim((string)$response);
        if (empty($response)) {
            return [];
        }
        $decodedResponse = json_decode($response, 1);
        if (!empty($decodedResponse) && is_array($decodedResponse)) {
            if (isset($decodedResponse['items'])) {
                // sometimes the records are not at array root, they are in array key "items"
                return $decodedResponse['items'];
            }
        }
        return $decodedResponse;
    }
}
