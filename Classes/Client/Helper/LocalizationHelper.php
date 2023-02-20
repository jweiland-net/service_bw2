<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
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
class LocalizationHelper implements SingletonInterface
{
    protected ExtConf $extConf;

    protected string $isoCode = '';

    public function __construct(ExtConf $extConf)
    {
        $this->extConf = $extConf;
    }

    /**
     * Get frontend language iso code
     *
     * Validates if current frontend language is an allowed language (extConf)
     * If TSFE is not initialized or current frontend language is not allowed,
     * returns the default language
     *
     * @return string current language or default language e.g. en
     */
    public function getFrontendLanguageIsoCode(): string
    {
        if ($this->isoCode === '') {
            $allowedLanguages = $this->extConf->getAllowedLanguages();
            reset($allowedLanguages);

            // Set a default for CLI requests
            $this->isoCode = key($allowedLanguages);

            // Override language, if we are in a web request
            if (
                $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface
                && $GLOBALS['TYPO3_REQUEST']->getAttribute('language') instanceof SiteLanguage
            ) {
                $currentLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getTwoLetterIsoCode();
                if (array_key_exists($currentLanguage, $allowedLanguages)) {
                    $this->isoCode = $currentLanguage;
                }
            }
        }

        return $this->isoCode;
    }
}
