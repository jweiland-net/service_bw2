<?php
namespace JWeiland\ServiceBw2\Service;

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

use JWeiland\ServiceBw2\Configuration\ExtConf;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class TranslationService
 *
 * @package JWeiland\ServiceBw2\Service
 */
class TranslationService implements SingletonInterface
{
    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @var string
     */
    protected $language = '';

    /**
     * inject extConf
     *
     * @param ExtConf $extConf
     * @return void
     */
    public function injectExtConf(ExtConf $extConf)
    {
        $this->extConf = $extConf;
    }

    /**
     * Initialize object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->language = $this->getFrontendLanguageIsoCode();
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
    protected function getFrontendLanguageIsoCode(): string
    {
        /** @var TypoScriptFrontendController $typoScriptFrontendController */
        $typoScriptFrontendController = $GLOBALS['TSFE'];
        $allowedLanguages = $this->extConf->getAllowedLanguages();
        $language = '';
        // Set current frontend language if TSFE is initialized
        if ($typoScriptFrontendController instanceof TypoScriptFrontendController) {
            $language = $typoScriptFrontendController->sys_language_isocode;
        }
        // Set default language if current $language is not in $allowedLanguages
        if (!array_key_exists($language, $allowedLanguages)) {
            reset($allowedLanguages);
            $language = key($allowedLanguages);
        }
        return $language;
    }

    /**
     * Prepare records for TYPO3 translation
     *
     * @param array $records
     * @param string $keyForId the value of $record[$keyForId] will be used as key for $records
     * @param bool $translateChildren translates entries inside _children by default, set false to disable
     * @return void
     */
    public function translate(array &$records, string $keyForId='id', bool $translateChildren = true)
    {
        foreach ($records as &$record) {
            if ($this->hasTranslations($record)) {
                $record = $this->getTranslation($record);
            }
            if ($translateChildren && array_key_exists('_children', $record)) {
                $this->translate($record['_children'], $keyForId, true);
            }
        }
    }

    /**
     * In i18n we find all additional translation fields
     * Use them to translate record with default language
     *
     * @param array $record
     * @param string $translationField ArrayKey with translations. Normally i18n
     * @param string $languageField ArrayKey where to find 2 letters language key. Normally sprache
     * @return array Return overlayed/translated record
     */
    protected function getTranslation(
        array $record,
        $translationField = 'i18n',
        $languageField = 'sprache'
    ): array
    {
        $additionalTranslationFields = [];
        $translatedRecord = $record;
        unset($translatedRecord['i18n']);
        if (
            array_key_exists($translationField, $record)
        ) {
            // get and prepare additional translation fields
            $additionalTranslationFields = [];
            foreach ($record[$translationField] as $fields) {
                $additionalTranslationFields = $fields;
                if ($fields[$languageField] === $this->language) {
                    break;
                }
            }
            unset($additionalTranslationFields[$languageField], $additionalTranslationFields['id']);
        }

        // overlay default translation with new translation
        $translatedRecord = array_merge($translatedRecord, $additionalTranslationFields);
        return $translatedRecord;
    }

    /**
     * Returns, if $record has translations
     *
     * @param array $record
     * @return bool
     */
    protected function hasTranslations(array $record)
    {
        return isset($record['i18n']) && is_array($record['i18n']) && !empty($record['i18n']);
    }

    /**
     * Returns Language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Sets Language
     *
     * Only needed if you want to override the current TSFE language!
     *
     * Attention: This does not validate $language against allowed languages in
     * extConf
     *
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }
}
