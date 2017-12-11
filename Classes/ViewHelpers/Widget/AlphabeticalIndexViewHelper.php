<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\ViewHelpers\Widget;

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

use JWeiland\ServiceBw2\ViewHelpers\Widget\Controller\AlphabeticalIndexController;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * This Widget provides you an alphabetical index list with navigation for bunch of records.
 * You can set fields like detailPageUid, action, controller and so on to setup the widget
 * for your own record. Take a look into initializeArguments() if you´re IDE doesn´t show
 * you the properties.
 *
 * Example:
 * {namespace jw=JWeiland\ServiceBw2\ViewHelpers}
 * <jw:widget.alphabeticalIndex detailPageUid="{settings.leistungen.pidOfDetailPage}" records="{leistungen}"
 *                              controller="Leistungen" action="show"/>
 *
 * @package JWeiland\ServiceBw2\ViewHelpers\Widget;
 */
class AlphabeticalIndexViewHelper extends AbstractWidgetViewHelper
{
    /**
     * @var AlphabeticalIndexController
     */
    protected $controller;

    /**
     * inject controller
     *
     * @param AlphabeticalIndexController $controller
     * @return void
     */
    public function injectController(AlphabeticalIndexController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('detailPageUid', 'string', 'UID of the detail page uid for a record', true);
        $this->registerArgument(
            'controller',
            'string',
            'Controller to be used to create the link for detail view',
            true
        );
        $this->registerArgument(
            'action',
            'string',
            'Action to be used to create the link for detail view',
            true
        );
        $this->registerArgument(
            'idField',
            'string',
            'Field to be used to create the link for detail view',
            false,
            'id'
        );
        $this->registerArgument(
            'titleField',
            'string',
            'Title field of a single record e.g. title or name',
            false,
            'displayName'
        );
        $this->registerArgument(
            'records',
            'array',
            'Records array that includes records like [123 => [...], 456 => [...]]',
            true
        );
    }

    /**
     * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
     */
    public function render()
    {
        return $this->initiateSubRequest();
    }
}
