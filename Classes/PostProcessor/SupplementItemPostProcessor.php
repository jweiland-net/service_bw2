<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\PostProcessor;

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
                $originalId = $leistung['landesZustaendigkeitId'] ?? null;
            } else {
                $leistung = $item;
                $originalId = $leistung['landesLeistungId'] ?? null;
            }

            // Unset original items if current item is a replacement of it and original id has been detected
            if (
                $originalId !== null
                && array_key_exists('type', $leistung)
                && array_key_exists($originalId, $response)
                && strtoupper($leistung['type']) === 'ERGAENZUNG'
            ) {
                unset($response[$originalId]);
            }
        }
        return $response;
    }
}
