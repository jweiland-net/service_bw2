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
     * @var string
     */
    protected $language = '';

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
     * @return string
     */
    protected function getFrontendLanguageIsoCode(): string
    {
        /** @var TypoScriptFrontendController $typoScriptFrontendController */
        $typoScriptFrontendController = $GLOBALS['TSFE'];
        return $typoScriptFrontendController->sys_language_isocode;
    }

    /**
     * Prepare records for TYPO3 translation
     *
     * @param array $records
     * @param string $keyForId the value of $record[$keyForId] will be used as key for $records
     * @return array
     */
    public function translate(array $records, string $keyForId='id'): array
    {
        $records = $this->sanitizeRecords($records);
        $translatedRecords = [];
        foreach ($records as $record) {
            if ($this->hasTranslations($record)) { // isset $item['id'] USE AS KEY
                $record = $this->getTranslation($record);
            }
            if (array_key_exists($keyForId, $record)) {
                $translatedRecords[$record[$keyForId]] = $record;
            } else {
                $translatedRecords[] = $record;
            }
        }
        return $translatedRecords;
    }

    /**
     * Add translated record to translations
     *
     * @param array $translatedRecords
     * @param array $value
     * @param string $language
     * @param string $keyForId
     * @deprecated  todo: remove
     * @return void
     */
    protected function addTranslatedRecord(array &$translatedRecords, array $value, $language = '', $keyForId = '')
    {
        if (array_key_exists($keyForId, $value)) {
            $translatedRecords[$language][$value[$keyForId]] = $value;
        } else {
            $translatedRecords[$language][] = $value;
        }

    }

    /**
     * Sanitize records
     *
     * @param array $records
     * @return array
     *
     * @see: allValuesAreArrays
     */
    protected function sanitizeRecords(array $records): array
    {
        return $this->allValuesAreArrays($records) ? $records: [$records];
    }

    /**
     * $this->translate can only work with following arrays
     * 0 => [id => 1]
     * 1 => [id => 3]
     * 2 => [id => 5]
     *
     * if we get something like:
     * id => 123
     * title => Hello
     * name => Stefan
     * this method will return false
     *
     * @param array $records
     * @return bool
     */
    protected function allValuesAreArrays(array $records): bool
    {
        return MathUtility::canBeInterpretedAsInteger(key($records));
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
                if ($fields[$languageField] === $this->language) {
                    $additionalTranslationFields = $fields;
                    break;
                }
            }
            unset($additionalTranslationFields[$languageField], $additionalTranslationFields['uid']);
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
}
