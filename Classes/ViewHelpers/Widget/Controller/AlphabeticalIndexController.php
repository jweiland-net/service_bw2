<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\ViewHelpers\Widget\Controller;

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

use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * Class AlphabeticalIndexController
 */
class AlphabeticalIndexController extends AbstractWidgetController
{
    public function indexAction()
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
