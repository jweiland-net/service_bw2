<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Configuration;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class, which contains the configuration from ExtensionManager
 */
class ExtConf implements SingletonInterface
{
    protected string $username = '';

    protected string $password = '';

    protected string $mandant = '';

    protected string $baseUrl = '';

    /**
     * Allowed languages.
     * First defined language = default language
     */
    protected array $allowedLanguages = [];

    protected int $ags = 0;

    protected string $gebietId = '';

    public function __construct()
    {
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('service_bw2');
        if (is_array($extConf)) {
            // call setter method foreach configuration entry
            foreach ($extConf as $key => $value) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = trim($password);
    }

    public function getMandant(): string
    {
        return $this->mandant;
    }

    public function setMandant(string $mandant): void
    {
        $this->mandant = trim($mandant);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function getAllowedLanguages(): array
    {
        return $this->allowedLanguages;
    }

    public function setAllowedLanguages(string $allowedLanguages): void
    {
        if (preg_match('@^([a-z]{2,2}=\d+;?)+$@', $allowedLanguages)) {
            $languageConfigurations = GeneralUtility::trimExplode(';', $allowedLanguages, true);
            foreach ($languageConfigurations as $languageConfiguration) {
                [$language, $sysLanguageUid] = GeneralUtility::trimExplode('=', $languageConfiguration, true);
                $this->allowedLanguages[$language] = (int)$sysLanguageUid;
            }
        }
    }

    public function getAgs(): int
    {
        return $this->ags;
    }

    /**
     * Amtlicher Gemeindeschluessel
     *
     * Sometimes this value is prefixed with 0 which is not valid
     * for requests. That's why we cast this value to int.
     */
    public function setAgs(string $ags): void
    {
        $this->ags = (int)$ags;
    }

    public function getGebietId(): string
    {
        return $this->gebietId;
    }

    public function setGebietId(string $gebietId): void
    {
        $this->gebietId = $gebietId;
    }
}
