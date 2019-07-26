<?php
declare(strict_types = 1);
namespace JWeiland\ServiceBw2\Configuration;

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

    public function __construct()
    {
        // get global configuration
        $extConf = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['service_bw2'],
            ['allowed_classes' => false]
        );
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

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = trim($username);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = trim($password);
    }

    /**
     * @return string
     */
    public function getMandant(): string
    {
        return $this->mandant;
    }

    /**
     * @param string $mandant
     */
    public function setMandant(string $mandant)
    {
        $this->mandant = trim($mandant);
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl)
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = (string)rtrim($baseUrl, '/');
    }

    /**
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        return $this->allowedLanguages;
    }

    /**
     * @param string $allowedLanguages
     */
    public function setAllowedLanguages(string $allowedLanguages)
    {
        if (preg_match('@^([a-z]{2,2}=\d+;?)+$@', $allowedLanguages)) {
            $languageConfigurations = GeneralUtility::trimExplode(';', $allowedLanguages, true);
            foreach ($languageConfigurations as $languageConfiguration) {
                list($language, $sysLanguageUid) = GeneralUtility::trimExplode('=', $languageConfiguration, true);
                $this->allowedLanguages[$language] = (int)$sysLanguageUid;
            }
        }
    }
}
