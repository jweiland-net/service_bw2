<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Event;

/*
 * An event to modify the response of a Service BW API request.
 *
 * The event will be fired before the response gets cached and before paginated requests get merged.
 */
final readonly class ModifyServiceBwResponseEvent
{
    public function __construct(
        private string $path,
        private array $responseBody,
        private bool $paginatedRequest = false,
        private bool $localizedRequest = false,
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function isPaginatedRequest(): bool
    {
        return $this->paginatedRequest;
    }

    public function isLocalizedRequest(): bool
    {
        return $this->localizedRequest;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }
}
