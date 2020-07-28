<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

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
    public function injectLebenslagenRepository(LebenslagenRepository $lebenslagenRepository): void
    {
        $this->lebenslagenRepository = $lebenslagenRepository;
    }

    /**
     * List action
     */
    public function listAction(): void
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
    public function showAction(int $id): void
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
