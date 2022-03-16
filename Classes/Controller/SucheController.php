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

class SucheController extends AbstractController
{
    /**
     * @var Suche
     */
    protected $suche;

    public function __construct(Suche $suche)
    {
        $this->suche = $suche;
    }

    public function listAction(string $query = '', string $sort = Suche::SORT_RELEVANZ, string $typ = Suche::TYP_NONE): void
    {
        $this->view->assignMultiple([
            'query' => $query,
            'result' => $query ? $this->suche->suche($query, $typ, $sort) : [],
            'sort' => $sort,
            'typ' => $typ
        ]);
    }
}
