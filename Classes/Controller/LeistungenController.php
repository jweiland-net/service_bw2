<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

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

    public function injectLeistungenRepository(LeistungenRepository $leistungenRepository): void
    {
        $this->leistungenRepository = $leistungenRepository;
    }

    public function injectExterneFormulareRepository(ExterneFormulareRepository $externeFormulareRepository): void
    {
        $this->externeFormulareRepository = $externeFormulareRepository;
    }

    public function injectOrganisationseinheitenRepository(
        OrganisationseinheitenRepository $organisationseinheitenRepository
    ): void {
        $this->organisationseinheitenRepository = $organisationseinheitenRepository;
    }

    /**
     * Show action
     *
     * @param int $id of Leistung
     */
    public function showAction(int $id): void
    {
        $regionIds = $this->extConf->getRegionIds();
        $mandantId = $this->extConf->getMandant();
        try {
            $leistung = $this->leistungenRepository->getLiveById($id);
            $externeFormulare = $this->externeFormulareRepository->getByLeistungAndRegion($id, $regionIds);
            $organisationseinheiten = $this->organisationseinheitenRepository->getRecordsByLeistungAndRegionId(
                $id,
                $regionIds,
                $mandantId
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

    /**
     * List action
     */
    public function listAction(): void
    {
        try {
            $this->view->assign('leistungen', $this->leistungenRepository->getAll());
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
        }
    }
}
