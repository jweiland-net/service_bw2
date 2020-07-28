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
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;

/**
 * Class OrganisationseinheitenController
 */
class OrganisationseinheitenController extends AbstractController
{
    /**
     * @var OrganisationseinheitenRepository
     */
    protected $organisationseinheitenRepository;

    /**
     * @var LeistungenRepository
     */
    protected $leistungenRepository;

    /**
     * @param OrganisationseinheitenRepository $organisationseinheitenRepository
     */
    public function injectOrganisationseinheitRepository(
        OrganisationseinheitenRepository $organisationseinheitenRepository
    ): void {
        $this->organisationseinheitenRepository = $organisationseinheitenRepository;
    }

    /**
     * @param LeistungenRepository $leistungenRepository
     */
    public function injectLeistungenRepository(LeistungenRepository $leistungenRepository): void
    {
        $this->leistungenRepository = $leistungenRepository;
    }

    /**
     * List action
     */
    public function listAction(): void
    {
        $listItems = json_decode('[' . $this->settings['organisationseinheiten']['listItems'] . ']', true);
        try {
            $records = $this->organisationseinheitenRepository->getRecordsWithChildren($listItems);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->view->assign('organisationseinheiten', $records);
    }

    /**
     * Show action
     *
     * @param int $id
     */
    public function showAction(int $id): void
    {
        try {
            $liveOrganisationseinheit = $this->organisationseinheitenRepository->getLiveOrganisationseinheitById($id);
            $organisationseinheit = $this->organisationseinheitenRepository->getById($id);
            $internetadressen = $this->organisationseinheitenRepository->getInternetadressenById($id);
            $children = $this->organisationseinheitenRepository->getChildren($id);
            $leistungen = $this->leistungenRepository->getByOrganisationseinheit($id);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->setPageTitle($organisationseinheit['name']);
        $this->view->assign('beschreibungstext', $liveOrganisationseinheit);
        $this->view->assign('organisationseinheit', $organisationseinheit);
        $this->view->assign('internetadressen', $internetadressen);
        $this->view->assign('children', $children);
        $this->view->assign('leistungen', $leistungen);
    }
}
