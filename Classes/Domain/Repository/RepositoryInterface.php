<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Domain\Model\Record;

interface RepositoryInterface
{
    public const CONTROLLER_TYPE = 'INVALID';

    public function findById(int $id): ?Record;

    public function hasId(int $id): bool;

    public function findAll(string $language): \Generator;

    public function addOrUpdate(array $record, string $language): void;

    public function getAllIds(string $language): array;

    public function deleteIds(array $ids, string $language): void;
}
