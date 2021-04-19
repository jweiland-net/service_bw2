<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;

/**
 * Class OrganisationseinheitenController
 */
class OrganisationseinheitenController extends AbstractController
{
    /**
     * @var Organisationseinheiten
     */
    protected $organisationseinheiten;

    public function __construct(Organisationseinheiten $organisationseinheiten)
    {
        $this->organisationseinheiten = $organisationseinheiten;
    }

    /**
     * List action
     */
    public function listAction(): void
    {
        $listItems = json_decode('[' . $this->settings['organisationseinheiten']['listItems'] . ']', true);
        $records = ServiceBwUtility::filterOrganisationseinheitenByParentIds($this->organisationseinheiten->findOrganisationseinheitenbaum(), $listItems);
        $this->view->assign('organisationseinheitenbaum', $records);
    }

    /**
     * Show action
     *
     * @param int $id
     */
    public function showAction(int $id): void
    {
        $organisationseinheit = $this->organisationseinheiten->findById($id);
        $this->setPageTitle($organisationseinheit['name']);
        $this->view->assign('organisationseinheit', $organisationseinheit);
    }
}
