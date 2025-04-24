<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

/**
 * There are several types (in Service BW Search called "typ") of entities in Service BW.
 * This abstract class adds the minimal required methods for it.
 */
interface EntityRequestInterface
{
    public function findById(int $id): array;

    public function findAll(): array;
}
