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
    protected $defaultLanguage = '';

    /**
     * inject extConf
     *
     * @param ExtConf $extConf
     *
     * @return void
     */
    public function injectExtConf(ExtConf $extConf)
    {
        $this->extConf = $extConf;
        $languages = $this->extConf->getAllowedLanguages();
        reset($languages);
        $this->defaultLanguage = key($languages);
    }

    /**
     * Prepare records for TYPO3 translation
     *
     * @param array $records
     *
     * @return array
     */
    public function translate(array $records)
    {
        $records = $this->sanitizeRecords($records);
        $translatedRecords = [];
        foreach ($records as $record) {
            if ($this->hasTranstations($record)) {
                foreach ($this->getTranslations($record) as $language => $translatedRecord) {
                    $this->addTranslatedRecord($translatedRecords, $translatedRecord, $language, 'id');
                }
            } else {
                foreach ($this->extConf->getAllowedLanguages() as $language => $sysLanguageUid) {
                    $this->addTranslatedRecord($translatedRecords, $record, $language, 'id');
                }
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
     *
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
     *
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
     *
     * @return bool
     */
    protected function allValuesAreArrays(array $records): bool
    {
        return MathUtility::canBeInterpretedAsInteger(key($records));
    }

    /**
     * Get all translations of record
     *
     * @param array $record
     *
     * @return array
     */
    protected function getTranslations($record)
    {
        $translatedRecords = [];
        $translatedRecord = $record;

        // all values of l18n will be added to cleanedRecord, so we can remove that key
        unset($translatedRecord['i18n']);
        $recordInDefaultLanguage = $translatedRecord;

        foreach ($this->extConf->getAllowedLanguages() as $allowedLanguage => $sysLanguageUid) {
            $translatedRecord = $this->overlayRecord($recordInDefaultLanguage, $record, $allowedLanguage);
            if ($this->defaultLanguage === $allowedLanguage) {
                $recordInDefaultLanguage = $translatedRecord;
            }
            $translatedRecords[$allowedLanguage] = $translatedRecord;
        }

        return $translatedRecords;
    }

    /**
     * Returns, if $record has translations
     *
     * @param array $record
     *
     * @return bool
     */
    protected function hasTranstations(array $record)
    {
        return isset($record['i18n']) && is_array($record['i18n']) && !empty($record['i18n']);
    }

    /**
     * In i18n we find all additional translation fields
     * Use them to translate record with default language
     *
     * @param array $recordInDefaultLanguage
     * @param array $recordFromServiceBwApi
     * @param string $language 2-letters language key like de, en or fr
     * @param string $translationField ArrayKey with translations. Normally i18n
     * @param string $languageField ArrayKey where to find 2 letters language key. Normally sprache
     *
     * @return array Return overlayed/translated record
     */
    protected function overlayRecord(array $recordInDefaultLanguage, array $recordFromServiceBwApi, $language = 'de', $translationField = 'i18n', $languageField = 'sprache')
    {
        $additionalTranslationFields = [];

        if (
            array_key_exists($language, $this->extConf->getAllowedLanguages())
            && array_key_exists($translationField, $recordFromServiceBwApi)
        ) {
            // get and prepare additional translation fields
            $additionalTranslationFields = [];
            foreach ($recordFromServiceBwApi[$translationField] as $fields) {
                if ($fields[$languageField] === $language) {
                    $additionalTranslationFields = $fields;
                    break;
                }
            }
            unset($additionalTranslationFields[$languageField], $additionalTranslationFields['uid']);
        }

        // overlay default translation with new translation
        $translatedRecord = array_merge($recordInDefaultLanguage, $additionalTranslationFields);

        // if translation is empty, do not add _languageUid
        if (!empty($translatedRecord)) {
            $translatedRecord['_languageUid'] = $this->extConf->getAllowedLanguages()[$language];
        }

        return $translatedRecord;
    }
}
