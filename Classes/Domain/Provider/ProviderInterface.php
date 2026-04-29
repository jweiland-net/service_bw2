<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Provider;

interface ProviderInterface
{
    public const CONTROLLER_TYPE = 'INVALID';

    public function findById(int $id, string $language): array;

    public function findAll(string $language): \Generator;
}
