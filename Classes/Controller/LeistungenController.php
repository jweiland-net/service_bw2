<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;

/**
 * Class LeistungenController
 */
class LeistungenController extends AbstractController
{
    protected Leistungen $leistungen;

    public function __construct(Leistungen $leistungen)
    {
        $this->leistungen = $leistungen;
    }

    /**
     * Show action
     *
     * @param int $id of Leistung
     */
    public function showAction(int $id): void
    {
        $leistung = $this->leistungen->findById($id);
        // todo: remove langauge labels, flexform setting, ...
//        $organisationseinheiten = ServiceBwUtility::removeItemsFromArray(
//            $organisationseinheiten,
//            explode(',', $this->settings['leistungen']['hideSelectedOrganisationseinheiten'] ?? '')
//        );
        $this->setPageTitle($leistung['name']);
        $this->view->assign('leistung', $leistung);
    }

    /**
     * List action
     */
    public function listAction(): void
    {
        $sortedLetterList = [];
        $sortedRecordList = [];
        AlphabeticalIndexUtility::createAlphabeticalIndex(
            $this->leistungen->findAll(),
            'name',
            $sortedLetterList,
            $sortedRecordList
        );
        $this->view->assign('sortedLetterList', $sortedLetterList);
        $this->view->assign('sortedRecordList', $sortedRecordList);
    }
}
