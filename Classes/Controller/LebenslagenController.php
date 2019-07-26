<?php
declare(strict_types = 1);
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

use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Request\Lebenslagen\References;

/**
 * Class LebenslagenController
 */
class LebenslagenController extends AbstractController
{
    /**
     * @var LebenslagenRepository
     */
    protected $lebenslagenRepository;

    /**
     * @param LebenslagenRepository $lebenslagenRepository
     */
    public function injectLebenslagenRepository(LebenslagenRepository $lebenslagenRepository)
    {
        $this->lebenslagenRepository = $lebenslagenRepository;
    }

    /**
     * List action
     */
    public function listAction()
    {
        try {
            $records = $this->lebenslagenRepository->getRoots();
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->view->assign('lebenslagen', $records);
    }

    /**
     * Show action
     *
     * @param int $id
     */
    public function showAction(int $id)
    {
        try {
            $lebenslage = $this->lebenslagenRepository->getById($id);
            $childLebenslagen = $this->lebenslagenRepository->getChildren($id);
            $liveLebenslage = $this->lebenslagenRepository->getLiveLebenslagen($id);
            $verfahrenReferences = $this->lebenslagenRepository->getReferences($id, References::TYPE_LEISTUNG);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->setPageTitle($lebenslage['displayName']);
        $this->view->assign('lebenslage', $lebenslage);
        $this->view->assign('childLebenslagen', $childLebenslagen);
        $this->view->assign('beschreibungstext', $liveLebenslage);
        $this->view->assign('verfahrenReferences', $verfahrenReferences);
    }
}
