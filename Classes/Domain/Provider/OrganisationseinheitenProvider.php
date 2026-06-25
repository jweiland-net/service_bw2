<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Provider;

use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheitenbaum;
use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheitsdetails;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Domain\Model\Record;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'service-bw2.provider',
)]
readonly class OrganisationseinheitenProvider implements ProviderInterface
{
    public const CONTROLLER_TYPE = 'organisationseinheiten';

    public function __construct(
        protected ServiceBwClient $client,
    ) {}

    public function findById(int $id, string $language): array
    {
        $request = new Organisationseinheitsdetails($id);

        return $this->client->requestRecord($request, $language);
    }

    public function findAll(string $language): \Generator
    {
        $request = new Organisationseinheiten();

        return $this->client->requestAll($request, $language);
    }

    /**
     * @return array<int, Record>
     */
    public function findOrganisationseinheitenTrees(string $language): array
    {
        $request = new Organisationseinheitenbaum();

        return array_map(
            fn(array $tree): Record => new Record(
                (int)($tree['id'] ?? 0),
                (string)($tree['name'] ?? ''),
                self::CONTROLLER_TYPE,
                $language,
                $tree,
            ),
            iterator_to_array($this->client->requestAll($request, $language)),
        );
    }
}
