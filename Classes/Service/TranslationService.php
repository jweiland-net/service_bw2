<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Service;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class TranslationService
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
     * Translation field
     *
     * @var string
     */
    protected $translationField = 'i18n';

    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    /**
     * Initialize object
     */
    public function initializeObject(): void
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
        $allowedLanguages = $this->extConf->getAllowedLanguages();
        reset($allowedLanguages);

        // Set a default for CLI requests
        $language = key($allowedLanguages);

        // Override language, if we are in a web request
        if (
            $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface
            && $GLOBALS['TYPO3_REQUEST']->getAttribute('language') instanceof SiteLanguage
        ) {
            $currentLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getTwoLetterIsoCode();
            if (array_key_exists($currentLanguage, $allowedLanguages)) {
                $language = $currentLanguage;
            }
        }

        return $language;
    }

    /**
     * Translate the records array
     *
     * @param array $records
     * @param bool $translateChildren translates entries inside _children by default, set true to enable
     * @param bool $translateRecursive
     */
    public function translateRecords(array &$records, bool $translateChildren = false, bool $translateRecursive = false): void
    {
        foreach ($records as &$record) {
            $record = $this->translate($record, $translateRecursive);
            if ($translateChildren && array_key_exists('_children', $record)) {
                $this->translateRecords($record['_children'], true);
            }
        }
    }

    /**
     * Translate the array $fields
     *
     * @param array $fields e.g. ['id' => 123, 'mandant' => 42, $this->translationField => [...]]
     * @param bool $translateRecursive set true to translate all arrays inside array $fields
     * @return array
     */
    public function translate(array $fields, bool $translateRecursive = false): array
    {
        if ($this->hasTranslations($fields)) {
            $fields = $this->getTranslation($fields);
        }
        if ($translateRecursive) {
            foreach ($fields as $key => &$field) {
                if (is_array($field) && $key !== '_children') {
                    $field = $this->translate($field, true);
                }
            }
        }
        return $fields;
    }

    /**
     * In i18n we find all additional translation fields
     * Use them to translate record with default language
     *
     * @param array $record
     * @param string $languageField ArrayKey where to find 2 letters language key. Normally sprache
     * @return array Return overlayed/translated record
     */
    protected function getTranslation(array $record, $languageField = 'sprache'): array
    {
        $additionalTranslationFields = [];
        $translatedRecord = $record;
        unset($translatedRecord[$this->translationField]);
        if (array_key_exists($this->translationField, $record)) {
            // get and prepare additional translation fields
            $additionalTranslationFields = [];
            foreach ($record[$this->translationField] as $fields) {
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
    protected function hasTranslations(array $record): bool
    {
        return isset($record[$this->translationField])
            && is_array($record[$this->translationField])
            && !empty($record[$this->translationField]);
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
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }
}
