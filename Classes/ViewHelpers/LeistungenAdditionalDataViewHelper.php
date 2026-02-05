<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\ViewHelpers;

use JWeiland\ServiceBw2\Helper\LeistungenHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get additional data for a Leistung record.
 */
final class LeistungenAdditionalDataViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function __construct(
        private LeistungenHelper $leistungenHelper,
    ) {}

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'id',
            'int',
            'ID of the Leistung record',
            true,
        );

        $this->registerArgument(
            'as',
            'string',
            'Name of the variable that contains the additional data',
            true,
        );
    }

    public function render()
    {
        $templateVariableContainer = $this->renderingContext->getVariableProvider();
        $templateVariableContainer->add(
            $this->arguments['as'],
            $this->leistungenHelper->getAdditionalData((int)$this->arguments['id']),
        );
        $output = $this->renderChildren();
        $templateVariableContainer->remove($this->arguments['as']);

        return $output;
    }
}
