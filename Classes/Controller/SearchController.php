<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Domain\Repository\SearchRepository;
use Psr\Http\Message\ResponseInterface;

class SearchController extends AbstractController
{
    public function __construct(
        protected SearchRepository $searchRepository,
    ) {}

    public function listAction(
        string $query = '',
        string $sort = SearchRepository::SORT_RELEVANZ,
        string $typ = SearchRepository::TYP_NONE,
    ): ResponseInterface {
        $variables = [
            'query' => $query,
            'result' => [],
            'sort' => $sort,
            'typ' => $typ,
        ];

        if ($query !== '' && $query !== '0') {
            $variables['result'] = $this->searchRepository->search($query, $typ, $sort);
        }

        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }
}
