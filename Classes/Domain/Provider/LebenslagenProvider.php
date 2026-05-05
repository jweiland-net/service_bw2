<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Provider;

use JWeiland\ServiceBw2\Client\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Client\Request\Portal\Lebenslagenbaum;
use JWeiland\ServiceBw2\Client\Request\Portal\Lebenslagendetails;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Domain\Model\Record;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'service-bw2.provider',
)]
readonly class LebenslagenProvider implements ProviderInterface
{
    public const CONTROLLER_TYPE = 'lebenslagen';

    public function __construct(
        protected ServiceBwClient $client,
    ) {}

    public function findById(int $id, string $language): array
    {
        $request = new Lebenslagendetails($id);

        return $this->client->requestRecord($request, $language);
    }

    public function findAll(string $language): \Generator
    {
        $request = new Lebenslagen();

        return $this->client->requestAll($request, $language);
    }

    public function findLebenslagenTrees(string $language): \Generator
    {
        $request = new Lebenslagenbaum();

        foreach ($this->client->requestAll($request, $language) as $root) {
            yield $root;
        }
    }
}
