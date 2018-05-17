<?php
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
 * Class ExtConf
 */
class ExtConf implements SingletonInterface
{
    /**
     * username
     *
     * @var string
     */
    protected $username = '';

    /**
     * password
     *
     * @var string
     */
    protected $password = '';

    /**
     * mandant
     *
     * @var string
     */
    protected $mandant = '';

    /**
     * baseUrl
     *
     * @var string
     */
    protected $baseUrl = '';

    /**
     * allowed languages.
     * First defined language = default language
     *
     * @var array
     */
    protected $allowedLanguages = [];

    /**
     * constructor of this class
     * This method reads the global configuration and calls the setter methods
     */
    public function __construct()
    {
        // get global configuration
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['service_bw2']);
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
     * Returns the username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = (string)trim($username);
    }

    /**
     * Returns the password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = (string)trim($password);
    }

    /**
     * Returns the mandant
     *
     * @return string $mandant
     */
    public function getMandant()
    {
        return $this->mandant;
    }

    /**
     * Sets the mandant
     *
     * @param string $mandant
     *
     * @return void
     */
    public function setMandant($mandant)
    {
        $this->mandant = (string)trim($mandant);
    }

    /**
     * Returns the baseUrl
     *
     * @return string $baseUrl
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Sets the baseUrl
     *
     * @param string $baseUrl
     *
     * @return void
     */
    public function setBaseUrl($baseUrl)
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = (string)rtrim($baseUrl, '/');
    }

    /**
     * Returns the allowedLanguages
     *
     * @return array $allowedLanguages
     */
    public function getAllowedLanguages()
    {
        return $this->allowedLanguages;
    }

    /**
     * Sets the allowedLanguages
     *
     * @param string $allowedLanguages
     *
     * @return void
     */
    public function setAllowedLanguages($allowedLanguages)
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
