<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Client\ServiceBwClient;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'service-bw2.repository',
)]
readonly class LebenslagenRepository implements RepositoryInterface
{
    public const CONTROLLER_TYPE = 'lebenslagen';

    public function __construct(
        protected ServiceBwClient $client,
    ) {}

    public function findById(int $id): array
    {
        return $this->client->request('/portal/lebenslagendetails/' . $id);
    }

    public function findAll(): array
    {
        return $this->client->request('/portal/lebenslagen', [], true, true);
    }

    public function findLebenslagenbaum(?int $ebenen = null): array
    {
        return $this->client->request(
            '/portal/lebenslagen/lebenslagenbaum',
            [
                'ebenen' => $ebenen,
            ],
            true,
            true,
        );
    }
}
