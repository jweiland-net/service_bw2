<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\ViewHelpers\Widget\Controller;

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

use JWeiland\ServiceBw2\Utility\AlphabeticalIndexUtility;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * Class AlphabeticalIndexController
 *
 * @package JWeiland\ServiceBw2\ViewHelpers\Widget\Controller;
 */
class AlphabeticalIndexController extends AbstractWidgetController
{
    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign(
            'alphabeticalNavigation',
            AlphabeticalIndexUtility::getNavigationHeader(
                $this->widgetConfiguration['records'],
                $this->widgetConfiguration['titleField']
            )
        );
        $this->view->assign(
            'recordList',
            AlphabeticalIndexUtility::getSortedRecordList(
                $this->widgetConfiguration['records'],
                $this->widgetConfiguration['titleField']
            )
        );
        $this->view->assign('detailPageUid', $this->widgetConfiguration['detailPageUid']);
        $this->view->assign('controller', $this->widgetConfiguration['controller']);
        $this->view->assign('action', $this->widgetConfiguration['action']);
        $this->view->assign('idField', $this->widgetConfiguration['idField']);
        $this->view->assign('titleField', $this->widgetConfiguration['titleField']);
    }
}
