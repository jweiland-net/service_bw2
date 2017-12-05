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
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class LeistungenController
 *
 * @package JWeiland\ServiceBw2\Controller;
 */
class LeistungenController extends AbstractController
{
    /**
     * @var LeistungenRepository
     */
    protected $leistungenRepository;

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
     * Show action
     *
     * @param int $id of Leistung
     * @return void
     */
    public function showAction(int $id)
    {
        try {
            $leistung = $this->leistungenRepository->getLiveById($id);
        } catch (\Exception $exception) {
            $this->addErrorWhileFetchingRecordsMessage($exception);
        }
        $this->view->assign('leistung', $leistung);
        DebuggerUtility::var_dump($leistung);
    }

    public function listAction()
    {

    }
}
