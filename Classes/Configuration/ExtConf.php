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
    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var string
     */
    protected $mandant = '';

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * Allowed languages.
     * First defined language = default language
     *
     * @var array
     */
    protected $allowedLanguages = [];

    /**
     * @var int
     */
    protected $ags = 0;

    /**
     * @var string
     */
    protected $gebietId = '';

    public function __construct()
    {
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('service_bw2');
        if (is_array($extConf) && count($extConf)) {
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
        $this->baseUrl = (string)rtrim($baseUrl, '/');
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
     *
     * @param string $ags
     */
    public function setAgs(string $ags): void
    {
        $this->ags = (int)$ags;
    }

    /**
     * @return string
     */
    public function getGebietId(): string
    {
        return $this->gebietId;
    }

    /**
     * @param string $gebietId
     */
    public function setGebietId(string $gebietId): void
    {
        $this->gebietId = $gebietId;
    }
}
