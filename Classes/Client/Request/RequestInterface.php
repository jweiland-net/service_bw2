<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Request;

/**
 * Interface for Service BW requests
 */
interface RequestInterface
{
    public const SUPPORTS_PAGINATION = false;

    public function getUrl(): string;

    public function getQuery(): array;

    public function getHeaders(): array;

    public function getBody(): ?string;
}
