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
use JWeiland\ServiceBw2\Helper\LanguageHelper;
use JWeiland\ServiceBw2\Service\AlphabeticalIndexService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class LeistungenController extends AbstractController
{
    public function __construct(
        protected LeistungenRepository $leistungenRepository,
        protected LanguageHelper $languageHelper,
        protected AlphabeticalIndexService $alphabeticalIndexService,
    ) {}

    public function showAction(int $id): ResponseInterface
    {
        $leistung = $this->leistungenRepository->findById($id);

        if ($leistung === null) {
            $this->addFlashMessage('Requested Leistung could not be found for current language');
        } else {
            $this->setPageTitle($leistung['name'] ?? '');
            $this->view->assign('leistung', $leistung);
        }

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $alphabeticalIndex = $this->alphabeticalIndexService->createAlphabeticalIndex(
            $this->leistungenRepository->findAll(
                $this->languageHelper->getServiceBwLanguageCodeFromRequest($this->request),
            ),
            'name',
        );

        $this->view->assignMultiple([
            'alphabeticalIndex' => $alphabeticalIndex,
            'request' => $this->request->getAttribute('extbase'),
        ]);

        return $this->htmlResponse();
    }
}
