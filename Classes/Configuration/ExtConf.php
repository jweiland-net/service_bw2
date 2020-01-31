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

use JWeiland\ServiceBw2\Domain\Repository\GebieteRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
     * @var array
     */
    protected $regionIds = [];

    /**
     * @var int
     */
    protected $ags = 0;

    /**
     * @var string
     */
    protected $plz = '';

    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['service_bw2'])) {
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
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = trim($username);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = trim($password);
    }

    public function getMandant(): string
    {
        return $this->mandant;
    }

    public function setMandant(string $mandant)
    {
        $this->mandant = trim($mandant);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl)
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = (string)rtrim($baseUrl, '/');
    }

    public function getAllowedLanguages(): array
    {
        return $this->allowedLanguages;
    }

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

    public function getRegionIds(): array
    {
        if (empty($this->regionIds)) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $gebieteRepository = $objectManager->get(GebieteRepository::class);
            if ($this->getAgs()) {
                $this->regionIds = current($gebieteRepository->getRegionIdsByAgs($this->getAgs()));
            } elseif ($this->getPlz()) {
                $this->regionIds = current($gebieteRepository->getRegionIdsByPlz($this->getPlz()));
            }
        }
        return $this->regionIds;
    }

    public function setRegionIds(string $regionIds)
    {
        $this->regionIds = GeneralUtility::trimExplode(',', $regionIds, true);
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
    public function setAgs(string $ags)
    {
        $this->ags = (int)$ags;
    }

    public function getPlz(): string
    {
        return $this->plz;
    }

    public function setPlz(string $plz)
    {
        $this->plz = $plz;
    }
}
