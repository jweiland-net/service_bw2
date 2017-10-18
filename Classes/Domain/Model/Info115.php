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
class Info115 extends AbstractEntity
{
    /**
     * This is NOT the ID of TYPO3 DB. It's the original ID from Service BW
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @var bool
     */
    protected $teilnehmer = false;

    /**
     * @var string
     */
    protected $teilnehmerNr = '';

    /**
     * @var string
     */
    protected $organisationsNummer = '';

    /**
     * @var string
     */
    protected $zusatzInfo = '';

    /**
     * @var string
     */
    protected $barriereFreiheit = '';

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
     * Returns the teilnehmer
     *
     * @return bool $teilnehmer
     */
    public function getTeilnehmer()
    {
        return $this->teilnehmer;
    }

    /**
     * Sets the teilnehmer
     *
     * @param bool $teilnehmer
     *
     * @return void
     */
    public function setTeilnehmer($teilnehmer)
    {
        $this->teilnehmer = (bool)$teilnehmer;
    }

    /**
     * Returns the teilnehmerNr
     *
     * @return string $teilnehmerNr
     */
    public function getTeilnehmerNr()
    {
        return $this->teilnehmerNr;
    }

    /**
     * Sets the teilnehmerNr
     *
     * @param string $teilnehmerNr
     *
     * @return void
     */
    public function setTeilnehmerNr($teilnehmerNr)
    {
        $this->teilnehmerNr = (string)$teilnehmerNr;
    }

    /**
     * Returns the organisationsNummer
     *
     * @return string $organisationsNummer
     */
    public function getOrganisationsNummer()
    {
        return $this->organisationsNummer;
    }

    /**
     * Sets the organisationsNummer
     *
     * @param string $organisationsNummer
     *
     * @return void
     */
    public function setOrganisationsNummer($organisationsNummer)
    {
        $this->organisationsNummer = (string)$organisationsNummer;
    }

    /**
     * Returns the zusatzInfo
     *
     * @return string $zusatzInfo
     */
    public function getZusatzInfo()
    {
        return $this->zusatzInfo;
    }

    /**
     * Sets the zusatzInfo
     *
     * @param string $zusatzInfo
     *
     * @return void
     */
    public function setZusatzInfo($zusatzInfo)
    {
        $this->zusatzInfo = (string)$zusatzInfo;
    }

    /**
     * Returns the barriereFreiheit
     *
     * @return string $barriereFreiheit
     */
    public function getBarriereFreiheit()
    {
        return $this->barriereFreiheit;
    }

    /**
     * Sets the barriereFreiheit
     *
     * @param string $barriereFreiheit
     *
     * @return void
     */
    public function setBarriereFreiheit($barriereFreiheit)
    {
        $this->barriereFreiheit = (string)$barriereFreiheit;
    }
}
