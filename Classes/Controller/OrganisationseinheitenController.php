<?php declare(strict_types=1);
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
 *
 * @package JWeiland\ServiceBw2\Controller;
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
     * inject organisationseinheitenRepository
     *
     * @param OrganisationseinheitenRepository $organisationseinheitenRepository
     * @return void
     */
    public function injectOrganisationseinheitRepository(
        OrganisationseinheitenRepository $organisationseinheitenRepository
    )
    {
        $this->organisationseinheitenRepository = $organisationseinheitenRepository;
    }

    /**
     * inject leistungenRepository
     *
     * @param LeistungenRepository $leistungenRepository
     * @return void
     */
    public function injectLeistungenRepository(LeistungenRepository $leistungenRepository)
    {
        $this->leistungenRepository = $leistungenRepository;
    }

    /**
     * List action
     *
     * @return void
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
     * @return void
     */
    public function showAction(int $id)
    {
        try {
            $liveOrganisationseinheit = $this->organisationseinheitenRepository->getLiveOrganisationseinheitById($id);
            $organisationseinheit = $this->organisationseinheitenRepository->getById($id);
            $children = $this->organisationseinheitenRepository->getChildren($id);
            $leistungen = $this->leistungenRepository->getByOrganisationseinheit($id);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->view->assign('beschreibungstext', $liveOrganisationseinheit);
        $this->view->assign('organisationseinheit', $organisationseinheit);
        $this->view->assign('children', $children);
        $this->view->assign('leistungen', $leistungen);
    }
}
