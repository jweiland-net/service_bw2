<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Client\Request\SearchSortPropertyEnum;
use JWeiland\ServiceBw2\Client\Request\SearchTypEnum;
use JWeiland\ServiceBw2\Client\Request\SortDirectionEnum;
use JWeiland\ServiceBw2\Domain\Repository\SearchRepository;
use JWeiland\ServiceBw2\Helper\LanguageHelper;
use Psr\Http\Message\ResponseInterface;

class SearchController extends AbstractController
{
    public function __construct(
        protected SearchRepository $searchRepository,
        protected LanguageHelper $languageHelper,
    ) {}

    public function listAction(
        string $searchTerm = '',
        SearchSortPropertyEnum $sortProperty = SearchSortPropertyEnum::RELEVANZ,
        ?SearchTypEnum $typ = null,
    ): ResponseInterface {
        $variables = [
            'searchTerm' => $searchTerm,
            'typ' => $typ === null ? '' : $typ->value,
            'sortProperty' => $sortProperty->value,
            'result' => [],
        ];

        if ($searchTerm !== '') {
            $language = $this->languageHelper->getServiceBwLanguageCodeFromRequest(
                $this->request,
            );

            $searchResults = $this->searchRepository->search(
                searchTerm: $searchTerm,
                language: $language,
                typ: $typ,
                sortProperty: $sortProperty,
                sortDirection: SortDirectionEnum::ASC,
            );

            $variables['searchResults'] = iterator_to_array($searchResults);
        }

        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }
}
