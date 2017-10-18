<?php
namespace JWeiland\ServiceBw2\PostProcessor;

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

use JWeiland\ServiceBw2\Service\TranslationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TranslatePostProcessor
 *
 * @package JWeiland\ServiceBw2\PostProcessor
 */
class TranslatePostProcessor extends AbstractPostProcessor
{
    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * inject translationService
     *
     * @param TranslationService $translationService
     *
     * @return void
     */
    public function injectTranslationService(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Post process response
     * Check, if record is translateable. If yes: make a copy of the record with another language
     *
     * @param array $response
     *
     * @return mixed
     */
    public function process($response)
    {
        if (is_array($response) && !empty($response)) {
            return $this->translationService->translate($response);
        } else {
            return $response;
        }
    }
}
