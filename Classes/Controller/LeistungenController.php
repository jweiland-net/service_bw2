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

use JWeiland\ServiceBw2\Domain\Repository\ExterneFormulareRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;

/**
 * Class LeistungenController
 */
class LeistungenController extends AbstractController
{
    /**
     * @var LeistungenRepository
     */
    protected $leistungenRepository;

    /**
     * @var ExterneFormulareRepository
     */
    protected $externeFormulareRepository;

    /**
     * @var OrganisationseinheitenRepository
     */
    protected $organisationseinheitenRepository;

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
     * inject externeFormulareRepository
     *
     * @param ExterneFormulareRepository $externeFormulareRepository
     * @return void
     */
    public function injectExterneFormulareRepository(ExterneFormulareRepository $externeFormulareRepository)
    {
        $this->externeFormulareRepository = $externeFormulareRepository;
    }

    /**
     * inject OrganisationseinheitenRepository
     *
     * @param OrganisationseinheitenRepository $organisationseinheitenRepository
     * @return void
     */
    public function injectOrganisationseinheitenRepository(OrganisationseinheitenRepository $organisationseinheitenRepository)
    {
        $this->organisationseinheitenRepository = $organisationseinheitenRepository;
    }

    /**
     * Show action
     *
     * @param int $id of Leistung
     * @return void
     */
    public function showAction(int $id)
    {
        $regionIds = (string)$this->extensionConfiguration['regionIds']['value'];
        $mandantId = (string)$this->extensionConfiguration['mandant']['value'];
        try {
            $leistung = $this->leistungenRepository->getLiveById($id);
            $externeFormulare = $this->externeFormulareRepository->getByLeistungAndRegion($id, $regionIds);
            $organisationseinheiten = $this->organisationseinheitenRepository->getRecordsByLeistungAndRegionId(
                $id,
                $regionIds
            );
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $organisationseinheiten = ServiceBwUtility::removeItemsFromArray(
            $organisationseinheiten,
            explode(',', $this->settings['leistungen']['hideSelectedOrganisationseinheiten'])
        );
        $this->setPageTitle($leistung['title']);
        $this->view->assign('leistung', $leistung);
        $this->view->assign('externeFormulare', $externeFormulare);
        $this->view->assign('organisationseinheiten', $organisationseinheiten);
    }

    public function listAction()
    {
        $this->view->assign('leistungen', $this->leistungenRepository->getAll());
    }
}
