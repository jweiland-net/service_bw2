<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Helper\ProzesseHelper;
use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;

/**
 * Controller for Prozesse from Service BW
 *
 * @deprecated in version 5.0, will be removed in 6.0. Use Leistungen (Services) plugin with filter options instead!
 */
class ProzesseController extends AbstractController
{
    /**
     * @var ProzesseHelper
     */
    protected $prozesseHelper;

    public function __construct(ProzesseHelper $prozesseHelper)
    {
        $this->prozesseHelper = $prozesseHelper;
        trigger_error(
            'service_bw2: The Prozesse mode is deprecated since version 5.0 and will be removed in 6.0!'
            . ' Use the Leistungen list with new filter options instead!',
            E_USER_DEPRECATED
        );
    }

    public function listAction(): void
    {
        $sortedLetterList = [];
        $sortedRecordList = [];
        AlphabeticalIndexUtility::createAlphabeticalIndex(
            $this->prozesseHelper->findAll(),
            'name',
            $sortedLetterList,
            $sortedRecordList
        );
        $this->view->assign('sortedLetterList', $sortedLetterList);
        $this->view->assign('sortedRecordList', $sortedRecordList);
    }
}
