<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Provider;

use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;

final readonly class ProviderFactory
{
    public function __construct(
        protected iterable $providers,
    ) {}

    public function getProvider(ControllerTypeEnum $controllerType): ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider::CONTROLLER_TYPE === $controllerType->value) {
                return $provider;
            }
        }

        throw new \InvalidArgumentException(
            'Could not find provider class for selected controller type "' . $controllerType->value . '"!',
            1777447606,
        );
    }

    /**
     * @return iterable<ProviderInterface>
     */
    public function getProviders(): iterable
    {
        return $this->providers;
    }
}
