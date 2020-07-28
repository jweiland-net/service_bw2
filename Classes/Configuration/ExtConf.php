<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Configuration;

use JWeiland\ServiceBw2\Domain\Repository\GebieteRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
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

    public function setRegionIds(string $regionIds): void
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
    public function setAgs(string $ags): void
    {
        $this->ags = (int)$ags;
    }

    public function getPlz(): string
    {
        return $this->plz;
    }

    public function setPlz(string $plz): void
    {
        $this->plz = $plz;
    }
}
