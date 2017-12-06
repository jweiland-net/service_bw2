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

use JWeiland\ServiceBw2\Domain\Repository\LebenslageRepository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class LebenslageController
 *
 * @package JWeiland\ServiceBw2\Controller
 */
class LebenslageController extends AbstractController
{
    /**
     * @var LebenslageRepository
     */
    protected $lebenslageRepository;

    /**
     * injects lebenslageRepository
     *
     * @param LebenslageRepository $lebenslageRepository
     * @return void
     */
    public function injectLebenslageRepository(LebenslageRepository $lebenslageRepository)
    {
        $this->lebenslageRepository = $lebenslageRepository;
    }

    /**
     * List action
     *
     * @return void
     */
    public function listAction()
    {
        try {
            $records = $this->lebenslageRepository->getAll();
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
            return;
        }
        $this->view->assign('lebenslagen', $records);
        DebuggerUtility::var_dump($records);
    }
}
