<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\ViewHelpers\Widget\Controller;

use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * Class AlphabeticalIndexController
 */
class AlphabeticalIndexController extends AbstractWidgetController
{
    public function indexAction(): void
    {
        $alphabeticalNavigation = [];
        $recordList = [];
        AlphabeticalIndexUtility::createAlphabeticalIndex(
            $this->widgetConfiguration['records'],
            $this->widgetConfiguration['titleField'],
            $alphabeticalNavigation,
            $recordList
        );
        $this->view->assign('alphabeticalNavigation', $alphabeticalNavigation);
        $this->view->assign('recordList', $recordList);
        $this->view->assign('detailPageUid', $this->widgetConfiguration['detailPageUid']);
        $this->view->assign('controller', $this->widgetConfiguration['controller']);
        $this->view->assign('action', $this->widgetConfiguration['action']);
        $this->view->assign('idField', $this->widgetConfiguration['idField']);
        $this->view->assign('titleField', $this->widgetConfiguration['titleField']);
    }
}
