<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Configuration;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class streamlines all settings from the extension manager
 */
#[Autoconfigure(constructor: 'create')]
readonly class ExtConf implements SingletonInterface
{
    private const EXT_KEY = 'service_bw2';

    private const DEFAULT_SETTINGS = [
        'mandant' => '',
        'token' => '',
        'baseUrl' => 'https://sgw.service-bw.de:443/rest-v2/api/',
        'allowedLanguages' => 'de=de;en=en;fr=fr',
        'ags' => 0,
        'gebietId' => '',
    ];

    public function __construct(
        private string $mandant = self::DEFAULT_SETTINGS['mandant'],
        private string $token = self::DEFAULT_SETTINGS['token'],
        private string $baseUrl = self::DEFAULT_SETTINGS['baseUrl'],
        private string $allowedLanguages = self::DEFAULT_SETTINGS['allowedLanguages'],
        private int $ags = self::DEFAULT_SETTINGS['ags'],
        private string $gebietId = self::DEFAULT_SETTINGS['gebietId'],
    ) {}

    public static function create(ExtensionConfiguration $extensionConfiguration): self
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        // Overwrite default extension settings with values from EXT_CONF
        try {
            $extensionSettings = array_merge(
                $extensionSettings,
                $extensionConfiguration->get(self::EXT_KEY),
            );
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
        }

        return new self(
            mandant: (string)$extensionSettings['mandant'],
            token: (string)$extensionSettings['token'],
            baseUrl: $extensionSettings['baseUrl'],
            allowedLanguages: (string)$extensionSettings['allowedLanguages'],

            // Sometimes this value is prefixed with 0, which is not valid
            // for requests. That's why we cast this value to int.
            ags: (int)$extensionSettings['ags'],
            gebietId: (string)$extensionSettings['gebietId'],
        );
    }

    public function getMandant(): string
    {
        return $this->mandant;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getBaseUrl(): string
    {
        return rtrim(trim((string)$this->baseUrl), '/');
    }

    /**
     * Returns allowed languages mapped from Service BW language codes to configured TYPO3 language codes.
     *
     * The array key contains the language code of the configured TYPO3 language, and the
     * array value contains the Service BW language code.
     *
     * @return array<string, string>
     */
    public function getAllowedLanguages(): array
    {
        // The first assigned language is the default language
        $languagesToProcess = $this->allowedLanguages;
        if (!preg_match('@^([a-z]{2,2}=[a-z]{2,2};?)+$@', $this->allowedLanguages)) {
            $languagesToProcess = self::DEFAULT_SETTINGS['allowedLanguages'];
        }

        $allowedLanguages = [];
        $languageConfigurations = GeneralUtility::trimExplode(';', $languagesToProcess, true);
        foreach ($languageConfigurations as $languageConfiguration) {
            [$typo3LanguageCode, $serviceBwLanguageCode] = explode('=', $languageConfiguration);
            $allowedLanguages[$typo3LanguageCode] = (int)$serviceBwLanguageCode;
        }

        return $allowedLanguages;
    }

    /**
     * Return "Amtlicher Gemeindeschluessel"
     */
    public function getAgs(): int
    {
        return $this->ags;
    }

    public function getGebietId(): string
    {
        return $this->gebietId;
    }

    public function getDefaultQueryForRequest(): array
    {
        $query = [
            'mandantId' => $this->getMandant(),
        ];

        if ($this->getAgs()) {
            $query['gebietAgs'] = $this->getAgs();
        }

        if ($this->getGebietId()) {
            $query['gebietId'] = $this->getGebietId();
        }

        return $query;
    }
}
