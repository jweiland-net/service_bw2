<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\PostProcessor;

use JWeiland\ServiceBw2\Exception\HttpResponseException;

/**
 * Class JsonPostProcessor
 */
class JsonPostProcessor extends AbstractPostProcessor
{
    /**
     * Post process json response
     *
     * @param mixed $response
     * @return array
     * @throws HttpResponseException if JSON decode fails
     */
    public function process($response): array
    {
        $response = trim((string)$response);
        if (empty($response)) {
            return [];
        }
        $decodedResponse = json_decode($response, true);
        if (!empty($decodedResponse) && \is_array($decodedResponse) && isset($decodedResponse['items'])) {
            // sometimes the records are not at array root, they are in array key "items"
            // if so then the array inside items will used as root and all other properties
            // will be copied into $arr['_root']
            $processedResponse = $decodedResponse['items'];
            unset($decodedResponse['items']);
            $processedResponse['_root'] = $decodedResponse;
            $decodedResponse = $processedResponse;
        } elseif ($decodedResponse === null) {
            // throw exception if json could not be decoded!
            throw new HttpResponseException(
                'Could not decode the JSON from HTTP response!',
                1525850941
            );
        }
        return $decodedResponse;
    }
}
