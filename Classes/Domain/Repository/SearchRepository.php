<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Client\Request\Portal\Suche;
use JWeiland\ServiceBw2\Client\Request\SearchSortPropertyEnum;
use JWeiland\ServiceBw2\Client\Request\SearchTypEnum;
use JWeiland\ServiceBw2\Client\Request\SortDirectionEnum;
use JWeiland\ServiceBw2\Client\ServiceBwClient;

readonly class SearchRepository
{
    public function __construct(
        protected ServiceBwClient $client,
    ) {}

    public function search(
        string $searchTerm,
        string $language,
        ?SearchTypEnum $typ,
        SearchSortPropertyEnum $sortProperty,
        SortDirectionEnum $sortDirection,
    ): \Generator {
        $request = new Suche(
            searchTerm: $searchTerm,
            typ: $typ,
            sortProperty: $sortProperty,
            sortDirection: $sortDirection,
        );

        return $this->client->requestAll($request, $language);
    }
}
