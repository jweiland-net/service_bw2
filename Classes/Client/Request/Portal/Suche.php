<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Request\Portal;

use JWeiland\ServiceBw2\Client\Request\RequestInterface;
use JWeiland\ServiceBw2\Client\Request\SearchSortPropertyEnum;
use JWeiland\ServiceBw2\Client\Request\SearchTypEnum;
use JWeiland\ServiceBw2\Client\Request\SortDirectionEnum;

final readonly class Suche implements RequestInterface
{
    public const SUPPORTS_PAGINATION = true;

    private const URL = '/portal/suche';

    public function __construct(
        private string $searchTerm = '',
        private ?SearchTypEnum $typ = null,
        private SearchSortPropertyEnum $sortProperty = SearchSortPropertyEnum::RELEVANZ,
        private SortDirectionEnum $sortDirection = SortDirectionEnum::ASC,
    ) {}

    public function getUrl(): string
    {
        return self::URL;
    }

    public function getQuery(): array
    {
        $query = [];

        if ($this->searchTerm !== '') {
            $query['q'] = $this->searchTerm;
        }

        if ($this->typ !== null) {
            $query['typ'] = $this->typ->value;
        }

        $query['sortProperty'] = $this->sortProperty->value;
        $query['sortDirection'] = $this->sortDirection->value;

        return $query;
    }

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getBody(): ?string
    {
        return null;
    }
}
