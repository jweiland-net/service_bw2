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
 * Interface PostProcessorInterface
 *
 * @package JWeiland\ServiceBw2\PostProcessor
 */
interface PostProcessorInterface
{
    /**
     * Post process bearer response
     *
     * @param string $response
     *
     * @return mixed
     */
    public function process($response);
}
