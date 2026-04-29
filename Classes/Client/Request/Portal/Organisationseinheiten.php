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

final readonly class Organisationseinheiten implements RequestInterface
{
    public const SUPPORTS_PAGINATION = true;

    private const URL = '/portal/organisationseinheiten';

    public function getUrl(): string
    {
        return self::URL;
    }

    public function getQuery(): array
    {
        return [
            'sortDirection' => 'asc',
            'sortProperty' => 'name',
        ];
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
