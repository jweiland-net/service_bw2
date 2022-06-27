<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
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
final class ModifyServiceBwResponseEvent
{
    /**
     * @var string
     */
    private $path = '';

    /**
     * @var array
     */
    private $responseBody = [];

    /**
     * @var bool
     */
    private $paginatedRequest = false;

    /**
     * @var bool
     */
    private $localizedRequest = false;

    public function __construct(string $path, array $responseBody, bool $paginatedRequest = false, bool $localizedRequest = false)
    {
        $this->path = $path;
        $this->responseBody = $responseBody;
        $this->paginatedRequest = $paginatedRequest;
        $this->localizedRequest = $localizedRequest;
    }

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

    public function setResponseBody(array $responseBody): void
    {
        $this->responseBody = $responseBody;
    }
}
