<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Domain\Provider\LebenslagenProvider;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Helper\LanguageHelper;
use Psr\Http\Message\ResponseInterface;

class LebenslagenController extends AbstractController
{
    public function __construct(
        protected LebenslagenRepository $lebenslagenRepository,
        protected LebenslagenProvider $lebenslagenProvider,
        protected LanguageHelper $languageHelper,
    ) {}

    public function listAction(): ResponseInterface
    {
        $languageCode = $this->languageHelper->getServiceBwLanguageCodeFromRequest($this->request);

        $this->view->assign(
            'lebenslagenTrees',
            $this->lebenslagenProvider->findLebenslagenTrees($languageCode),
        );

        return $this->htmlResponse();
    }

    public function showAction(int $id): ResponseInterface
    {
        $lebenslage = $this->lebenslagenRepository->findById($id);

        if (!$lebenslage instanceof Record) {
            $this->addFlashMessage('Requested Lebenslage could not be found for current language');
        } else {
            $this->view->assign('lebenslage', $lebenslage);
            $this->setPageTitle($lebenslage->getName());
        }

        return $this->htmlResponse();
    }
}
