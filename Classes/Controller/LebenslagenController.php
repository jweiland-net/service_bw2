<?php
declare(strict_types = 1);
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

use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Request\Lebenslagen\References;

/**
 * Class LebenslageController
 *
 * @package JWeiland\ServiceBw2\Controller
 */
class LebenslagenController extends AbstractController
{
    /**
     * @var LebenslagenRepository
     */
    protected $lebenslagenRepository;

    /**
     * injects lebenslagenRepository
     *
     * @param LebenslagenRepository $lebenslagenRepository
     * @return void
     */
    public function injectLebenslagenRepository(LebenslagenRepository $lebenslagenRepository)
    {
        $this->lebenslagenRepository = $lebenslagenRepository;
    }

    /**
     * List action
     *
     * @return void
     */
    public function listAction()
    {
        try {
            $records = $this->lebenslagenRepository->getAll();
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
     * @return void
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

        $this->view->assign('lebenslage', $lebenslage);
        $this->view->assign('childLebenslagen', $childLebenslagen);
        $this->view->assign('beschreibungstext', $liveLebenslage);
        $this->view->assign('verfahrenReferences', $verfahrenReferences);
    }
}
