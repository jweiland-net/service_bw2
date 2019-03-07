<?php
declare(strict_types = 1);
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
 * Post processor to remove default "Leistungen" items if they have supplements.
 */
class SupplementItemPostProcessor extends AbstractPostProcessor
{
    /**
     * @param mixed $response
     * @return array
     */
    public function process($response): array
    {
        $response = (array)$response;
        foreach ($response as $key => $item) {
            // Check if leistung is a leistung or a zustaendigkeit with a leistung as array item
            if (array_key_exists('leistung', $item) && is_array($item['leistung'])) {
                $leistung = $item['leistung'];
                $originalId = $leistung['landesZustaendigkeitId'];
            } else {
                $leistung = $item;
                $originalId = $leistung['landesLeistungId'];
            }

            // Unset original items if current item is a replacement of it
            if (
                array_key_exists('type', $leistung)
                && array_key_exists($originalId, $response)
                && $leistung['type'] === 'ERGAENZUNG'
            ) {
                unset($response[$originalId]);
            }
        }
        return $response;
    }
}
