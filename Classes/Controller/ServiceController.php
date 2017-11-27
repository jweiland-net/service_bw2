<?php
namespace JWeiland\ServiceBw2\Controller;

/*
 * This file is part of the service_bw2 project.
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
use JWeiland\ServiceBw2\Domain\Repository\OrganisationsEinheitRepository;
use JWeiland\ServiceBw2\Request;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class ServiceController
 *
 * @package JWeiland\ServiceBw2\Controller
 */
class ServiceController extends AbstractController
{
    /**
     * @var OrganisationsEinheitRepository
     */
    protected $organizationalUnitRepository;

    /**
     * inject organizationalUnitRepository
     *
     * @param OrganisationsEinheitRepository $organizationalUnitRepository
     * @return void
     */
    public function injectOrganizationalUnitRepository(OrganisationsEinheitRepository $organizationalUnitRepository)
    {
        $this->organizationalUnitRepository = $organizationalUnitRepository;
    }

    /**
     * Overview action
     *
     * @return void
     */
    public function overviewAction()
    {
        // todo: remove !!!1
        $GLOBALS['DEBUG_KRONOVA']['json_decode'] = 0;
        $GLOBALS['DEBUG_KRONOVA']['post_processors'] = 0;
        $GLOBALS['DEBUG_KRONOVA']['kinder_bekommen'] = [];
        $GLOBALS['DEBUG_KRONOVA']['kinder_bekommen']['TOTAL'] = 0;

        DebuggerUtility::var_dump($this->organizationalUnitRepository->getAll());
        DebuggerUtility::var_dump($GLOBALS['DEBUG_KRONOVA']);
    }

    /**
     * List organizational Units
     *
     * @return void
     */
    public function listOrganizationalUnitsAction()
    {
        $this->view->assign('organizationalUnits', $this->organizationalUnitRepository->getAll());
    }

    public function responsibilityFinderAction()
    {

    }
}
