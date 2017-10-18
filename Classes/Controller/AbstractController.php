<?php
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
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Class AbstractController
 *
 * @package JWeiland\ServiceBw2\Controller
 */
abstract class AbstractController extends ActionController
{
    /**
     * Backend Template Container
     *
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var int the current page id
     */
    protected $id = 0;

    /**
     * Initializes view
     *
     * @param ViewInterface $view The view to be initialized
     *
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        $this->registerDocHeaderButtons();
    }

    /**
     * Initializes the controller before invoking an action method.
     *
     * @return void
     */
    protected function initializeAction()
    {
        // determine id parameter
        $this->id = (int)GeneralUtility::_GP('id');
        if ($this->request->hasArgument('id')) {
            $this->id = (int)$this->request->getArgument('id');
        }
    }

    /**
     * Registers the Icons into the DocHeader
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function registerDocHeaderButtons()
    {
        /** @var ButtonBar $buttonBar */
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        $overviewButton = $buttonBar->makeInputButton()
            ->setName('overview')
            ->setValue('1')
            ->setTitle('Overview')
            ->setOnClick($this->createLink('overview'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon(
                'actions-document-close',
                Icon::SIZE_SMALL
            ));

        $listOrganizationalUnitsButton = $buttonBar->makeInputButton()
            ->setName('organizationalUnit')
            ->setValue('1')
            ->setTitle('Organizational Unit')
            ->setOnClick($this->createLink('listOrganizationalUnits'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon(
                'actions-document-close',
                Icon::SIZE_SMALL
            ));

        $responsibilityFinderButton = $buttonBar->makeInputButton()
            ->setName('responsibilityFinder')
            ->setValue('1')
            ->setTitle('Responsibility Finder')
            ->setOnClick($this->createLink('responsibilityFinder'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon(
                'actions-document-close',
                Icon::SIZE_SMALL
            ));

        $splitButton = $buttonBar->makeSplitButton()
            ->addItem($overviewButton)
            ->addItem($listOrganizationalUnitsButton)
            ->addItem($responsibilityFinderButton);
        $buttonBar->addButton($splitButton);
    }

    /**
     * Create quoted link
     *
     * @param string $action
     *
     * @return string
     */
    protected function createLink($action)
    {
        return 'window.location.href=' . GeneralUtility::quoteJSvalue(
                $this->uriBuilder
                    ->reset()
                    ->setTargetPageUid($this->id)
                    ->uriFor($action)
            );
    }
}
