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
use JWeiland\ServiceBw2\Domain\Repository\OrganisationsEinheitRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class OrganizationalUnitController
 *
 * @package JWeiland\ServiceBw2\Controller;
 */
class OrganisationsEinheitController extends ActionController
{
    /**
     * @var OrganisationsEinheitRepository
     */
    protected $organisationsEinheitRepository;

    /**
     * @var LeistungenRepository
     */
    protected $leistungenRepository;

    /**
     * inject organisationsEinheitRepository
     *
     * @param OrganisationsEinheitRepository $organisationsEinheitRepository
     * @return void
     */
    public function injectOrganisationsEinheitRepository(OrganisationsEinheitRepository $organisationsEinheitRepository)
    {
        $this->organisationsEinheitRepository = $organisationsEinheitRepository;
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
        $listItems = json_decode('[' . $this->settings['organisationsEinheit']['listItems'] . ']', true);
        try {
            $records = $this->organisationsEinheitRepository->getRecordsWithChildren($listItems);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->view->assign('organisationsEinheiten', $records);
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
            $liveOrganisationsEinheit = $this->organisationsEinheitRepository->getLiveOrganisationsEinheitById($id);
            $oranigsationsEinheit = $this->organisationsEinheitRepository->getById($id);
            $leistungen = $this->leistungenRepository->getByOrganisationsEinheit($id);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        // todo: add maps2
        //$this->organisationsEinheitRepository->getMaps2PoiCollection($id);
        $this->view->assign('leistungen', $leistungen);
        $this->view->assign('beschreibungstext', $liveOrganisationsEinheit);
        $this->view->assign('organisationsEinheit', $oranigsationsEinheit);
    }

    /**
     * Add "error while fetching records" error message
     *
     * @param \Exception $exception
     * @return void
     */
    protected function addErrorWhileFetchingRecordsMessage(\Exception $exception)
    {
        $this->addFlashMessage(
            LocalizationUtility::translate('error_message.error_while_fetching_records', 'service_bw2'),
            '',
            AbstractMessage::ERROR
        );
        GeneralUtility::sysLog(
            'Got the following exception while fetching records: ' . $exception->getMessage(),
            'service_bw2',
            GeneralUtility::SYSLOG_SEVERITY_ERROR
        );
    }
}
