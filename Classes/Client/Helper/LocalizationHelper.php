<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Helper;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

/**
 * Helper to get the required language iso code for localized requests
 *
 * @internal
 */
readonly class LocalizationHelper implements SingletonInterface
{
    public function __construct(
        protected ExtConf $extConf,
    ) {}

    /**
     * Retrieves the ISO code of the current frontend language.
     *
     * Validates that the active frontend language is listed among the allowed languages
     * configured via extension settings (extConf). If the SiteLanguage is not available
     * or the active language is not permitted, the default language is returned. If no
     * allowed languages are configured, an empty string is returned.
     */
    public function getFrontendLanguageIsoCode(): string
    {
        $allowedLanguages = $this->extConf->getAllowedLanguages();
        if ($allowedLanguages === []) {
            return '';
        }

        // Set a default for CLI requests
        $isoCode = array_key_first($allowedLanguages);

        // Override language if we are in a web request
        if (
            $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface
            && $GLOBALS['TYPO3_REQUEST']->getAttribute('language') instanceof SiteLanguage
        ) {
            $currentLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getLocale()->getLanguageCode();
            if (array_key_exists($currentLanguage, $allowedLanguages)) {
                $isoCode = $currentLanguage;
            }
        }

        return $isoCode;
    }
}
