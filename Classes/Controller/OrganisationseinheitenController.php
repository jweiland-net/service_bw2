<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Domain\Provider\OrganisationseinheitenProvider;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Helper\LanguageHelper;
use JWeiland\ServiceBw2\Traits\FilterOrganisationseinheitenTrait;
use Psr\Http\Message\ResponseInterface;

class OrganisationseinheitenController extends AbstractController
{
    use FilterOrganisationseinheitenTrait;

    public function __construct(
        protected OrganisationseinheitenRepository $organisationseinheitenRepository,
        protected OrganisationseinheitenProvider $organisationseinheitenProvider,
        protected LanguageHelper $languageHelper,
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
        } catch (\JsonException) {
            $listItems = [];
        }

        $languageCode = $this->languageHelper->getServiceBwLanguageCodeFromRequest($this->request);
        $records = $this->filterOrganisationseinheitenByParentIds(
            $this->organisationseinheitenProvider->findOrganisationseinheitenTrees(
                $languageCode,
            ),
            $listItems,
            $languageCode,
        );

        $this->view->assign('organisationseinheitenTrees', $records);

        return $this->htmlResponse();
    }

    public function showAction(int $id): ResponseInterface
    {
        $record = $this->organisationseinheitenRepository->findById($id);

        if ($record === null) {
            $this->addFlashMessage('Requested Organisationseinheit could not be found for current language');
        } else {
            $this->setPageTitle($record->getName());
            $this->view->assign('organisationseinheit', $record);
        }

        return $this->htmlResponse();
    }
}
