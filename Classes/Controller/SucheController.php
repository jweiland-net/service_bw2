<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Request\Portal\Suche;
use Psr\Http\Message\ResponseInterface;

class SucheController extends AbstractController
{
    protected Suche $suche;

    public function injectSuche(Suche $suche): void
    {
        $this->suche = $suche;
    }

    public function listAction(
        string $query = '',
        string $sort = Suche::SORT_RELEVANZ,
        string $typ = Suche::TYP_NONE,
    ): ResponseInterface {
        $this->view->assignMultiple([
            'query' => $query,
            'result' => $query !== '' && $query !== '0' ? $this->suche->suche($query, $typ, $sort) : [],
            'sort' => $sort,
            'typ' => $typ,
        ]);

        return $this->htmlResponse();
    }
}
