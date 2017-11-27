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

use JWeiland\ServiceBw2\Domain\Repository\OrganisationsEinheitRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
     * List action
     *
     * @return void
     */
    public function listAction()
    {
        $this->view->assign('organisationsEinheiten', $this->organisationsEinheitRepository->getAll());
    }
}
