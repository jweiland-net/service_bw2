<?php

namespace JWeiland\ServiceBw2\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class VerwaltungspolitischeKodierung extends AbstractEntity
{
    /**
     * This is NOT the ID of TYPO3 DB. It's the original ID from Service BW
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $kreisdestatis;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $bezirk;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $bundesland;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $gemeindeschluessel;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $regionalschluessel;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Schluessel
     */
    protected $staat;

    /**
     * @var string
     */
    protected $gemeindedeteilschluesselCode = '';

    /**
     * @var string
     */
    protected $gemeindedeteilschluesselName = '';

    /**
     * Returns the id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Returns the kreisdestatis
     *
     * @return Schluessel $kreisdestatis
     */
    public function getKreisdestatis()
    {
        return $this->kreisdestatis;
    }

    /**
     * Sets the kreisdestatis
     *
     * @param Schluessel $kreisdestatis
     *
     * @return void
     */
    public function setKreisdestatis(Schluessel $kreisdestatis = null)
    {
        $this->kreisdestatis = $kreisdestatis;
    }

    /**
     * Returns the bezirk
     *
     * @return Schluessel $bezirk
     */
    public function getBezirk()
    {
        return $this->bezirk;
    }

    /**
     * Sets the bezirk
     *
     * @param Schluessel $bezirk
     *
     * @return void
     */
    public function setBezirk(Schluessel $bezirk = null)
    {
        $this->bezirk = $bezirk;
    }

    /**
     * Returns the bundesland
     *
     * @return Schluessel $bundesland
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }

    /**
     * Sets the bundesland
     *
     * @param Schluessel $bundesland
     *
     * @return void
     */
    public function setBundesland(Schluessel $bundesland = null)
    {
        $this->bundesland = $bundesland;
    }

    /**
     * Returns the gemeindeschluessel
     *
     * @return Schluessel $gemeindeschluessel
     */
    public function getGemeindeschluessel()
    {
        return $this->gemeindeschluessel;
    }

    /**
     * Sets the gemeindeschluessel
     *
     * @param Schluessel $gemeindeschluessel
     *
     * @return void
     */
    public function setGemeindeschluessel(Schluessel $gemeindeschluessel = null)
    {
        $this->gemeindeschluessel = $gemeindeschluessel;
    }

    /**
     * Returns the regionalschluessel
     *
     * @return Schluessel $regionalschluessel
     */
    public function getRegionalschluessel()
    {
        return $this->regionalschluessel;
    }

    /**
     * Sets the regionalschluessel
     *
     * @param Schluessel $regionalschluessel
     *
     * @return void
     */
    public function setRegionalschluessel(Schluessel $regionalschluessel = null)
    {
        $this->regionalschluessel = $regionalschluessel;
    }

    /**
     * Returns the staat
     *
     * @return Schluessel $staat
     */
    public function getStaat()
    {
        return $this->staat;
    }

    /**
     * Sets the staat
     *
     * @param Schluessel $staat
     *
     * @return void
     */
    public function setStaat(Schluessel $staat = null)
    {
        $this->staat = $staat;
    }

    /**
     * Returns the gemeindedeteilschluesselCode
     *
     * @return string $gemeindedeteilschluesselCode
     */
    public function getGemeindedeteilschluesselCode()
    {
        return $this->gemeindedeteilschluesselCode;
    }

    /**
     * Sets the gemeindedeteilschluesselCode
     *
     * @param string $gemeindedeteilschluesselCode
     *
     * @return void
     */
    public function setGemeindedeteilschluesselCode($gemeindedeteilschluesselCode)
    {
        $this->gemeindedeteilschluesselCode = (string)$gemeindedeteilschluesselCode;
    }

    /**
     * Returns the gemeindedeteilschluesselName
     *
     * @return string $gemeindedeteilschluesselName
     */
    public function getGemeindedeteilschluesselName()
    {
        return $this->gemeindedeteilschluesselName;
    }

    /**
     * Sets the gemeindedeteilschluesselName
     *
     * @param string $gemeindedeteilschluesselName
     *
     * @return void
     */
    public function setGemeindedeteilschluesselName($gemeindedeteilschluesselName)
    {
        $this->gemeindedeteilschluesselName = (string)$gemeindedeteilschluesselName;
    }
}
