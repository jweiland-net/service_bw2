<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;

final readonly class RepositoryFactory
{
    public function __construct(
        private iterable $repositories,
    ) {}

    public function getRepository(ControllerTypeEnum $controllerType): RepositoryInterface
    {
        foreach ($this->repositories as $repository) {
            if ($repository::CONTROLLER_TYPE === $controllerType->value) {
                return $repository;
            }
        }

        throw new \InvalidArgumentException(
            'Could not find repository for selected controller type "' . $controllerType->value . '"!',
            1523960421,
        );
    }

    /**
     * @return iterable<RepositoryInterface>
     */
    public function getRepositories(): iterable
    {
        return $this->repositories;
    }
}
