<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;
use Psr\Http\Message\ResponseInterface;

class LeistungenController extends AbstractController
{
    public function __construct(
        protected LeistungenRepository $leistungenRepository,
    ) {}

    public function showAction(int $id): ResponseInterface
    {
        $leistung = $this->leistungenRepository->findById($id);

        if ($leistung === []) {
            $this->addFlashMessage('Requested Leistung could not be found for current language');
        } else {
            $this->setPageTitle($leistung['name'] ?? '');
            $this->view->assign('leistung', $leistung);
        }

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $sortedLetterList = [];
        $sortedRecordList = [];
        AlphabeticalIndexUtility::createAlphabeticalIndex(
            $this->leistungenRepository->findAll(),
            'name',
            $sortedLetterList,
            $sortedRecordList,
        );

        $this->view->assign('sortedLetterList', $sortedLetterList);
        $this->view->assign('sortedRecordList', $sortedRecordList);

        return $this->htmlResponse();
    }
}
