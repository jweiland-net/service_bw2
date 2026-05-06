<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Request\Portal;

use JWeiland\ServiceBw2\Client\Request\RequestInterface;

final readonly class Lebenslagendetails implements RequestInterface
{
    public const SUPPORTS_PAGINATION = false;

    private const URL = '/portal/lebenslagendetails/%s';

    public function __construct(
        private int $id,
    ) {}

    public function getUrl(): string
    {
        return sprintf(
            self::URL,
            $this->id,
        );
    }

    public function getQuery(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getBody(): ?string
    {
        return null;
    }
}
