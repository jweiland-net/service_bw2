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
        protected iterable $repositoryClasses,
    ) {}

    public function getRepository(ControllerTypeEnum $controllerType): RepositoryInterface
    {
        foreach ($this->repositoryClasses as $repositoryClass) {
            if ($repositoryClass::CONTROLLER_TYPE === $controllerType->value) {
                return $repositoryClass;
            }
        }

        throw new \InvalidArgumentException(
            'Could not find repository class for selected controller type "' . $controllerType->value . '"!',
            1523960421,
        );
    }

    /**
     * @return iterable<RepositoryInterface>
     */
    public function getRepositories(): iterable
    {
        return $this->repositoryClasses;
    }
}
