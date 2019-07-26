<?php
declare(strict_types=1);
namespace JWeiland\ServiceBw2\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
    )
    {
        $this->organisationseinheitenRepository = $organisationseinheitenRepository;
    }

    /**
     * @param LeistungenRepository $leistungenRepository
     */
    public function injectLeistungenRepository(LeistungenRepository $leistungenRepository)
    {
        $this->leistungenRepository = $leistungenRepository;
    }

    /**
     * List action
     */
    public function listAction()
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
    public function showAction(int $id)
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
