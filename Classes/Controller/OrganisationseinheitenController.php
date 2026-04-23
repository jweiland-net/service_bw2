<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;
use Psr\Http\Message\ResponseInterface;

class OrganisationseinheitenController extends AbstractController
{
    public function __construct(
        protected OrganisationseinheitenRepository $organisationseinheitenRepository,
    ) {}

    public function listAction(): ResponseInterface
    {
        try {
            $listItems = json_decode(
                '[' . $this->settings['organisationseinheiten']['listItems'] . ']',
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException $jsonException) {
            $listItems = [];
        }

        $records = ServiceBwUtility::filterOrganisationseinheitenByParentIds(
            $this->organisationseinheitenRepository->findOrganisationseinheitenbaum(),
            $listItems,
        );

        $this->view->assign('organisationseinheitenbaum', $records);

        return $this->htmlResponse();
    }

    public function showAction(int $id): ResponseInterface
    {
        $organisationseinheit = $this->organisationseinheitenRepository->findById($id);

        if ($organisationseinheit === []) {
            $this->addFlashMessage('Requested Organisationseinheit could not be found for current language');
        } else {
            $this->setPageTitle($organisationseinheit['name'] ?? '');
            $this->view->assign('organisationseinheit', $organisationseinheit);
        }

        return $this->htmlResponse();
    }
}
