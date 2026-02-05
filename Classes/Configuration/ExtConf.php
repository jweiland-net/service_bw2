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
        'username' => '',
        'password' => '',
        'mandant' => '',
        'baseUrl' => 'https://sgw.service-bw.de:443/',
        'allowedLanguages' => 'de=0;en=1;fr=2',
        'ags' => 0,
        'gebietId' => '',
    ];

    public function __construct(
        private string $username = self::DEFAULT_SETTINGS['username'],
        private string $password = self::DEFAULT_SETTINGS['password'],
        private string $mandant = self::DEFAULT_SETTINGS['mandant'],
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
            username: (string)$extensionSettings['username'],
            password: (string)$extensionSettings['password'],
            mandant: (string)$extensionSettings['mandant'],
            baseUrl: $extensionSettings['baseUrl'],
            allowedLanguages: (string)$extensionSettings['allowedLanguages'],

            // Sometimes this value is prefixed with 0, which is not valid
            // for requests. That's why we cast this value to int.
            ags: (int)$extensionSettings['ags'],
            gebietId: (string)$extensionSettings['gebietId'],
        );
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMandant(): string
    {
        return $this->mandant;
    }

    public function getBaseUrl(): string
    {
        return rtrim(trim((string)$this->baseUrl), '/');
    }

    public function getAllowedLanguages(): array
    {
        // The first assigned language is the default language
        $languagesToProcess = $this->allowedLanguages;
        if (!preg_match('@^([a-z]{2,2}=\d+;?)+$@', $this->allowedLanguages)) {
            $languagesToProcess = self::DEFAULT_SETTINGS['allowedLanguages'];
        }

        $allowedLanguages = [];
        $languageConfigurations = GeneralUtility::trimExplode(';', $languagesToProcess, true);
        foreach ($languageConfigurations as $languageConfiguration) {
            [$language, $sysLanguageUid] = explode('=', $languageConfiguration);
            $allowedLanguages[$language] = (int)$sysLanguageUid;
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
}
