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
class InternetAdresse extends AbstractEntity
{
    /**
     * This is NOT the ID of TYPO3 DB. It's the original ID from Service BW
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $mandant = '';

    /**
     * @var bool
     */
    protected $kennzeichenAnzeigeNeuesFenster = false;

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var string
     */
    protected $titel = '';

    /**
     * @var string
     */
    protected $beschreibung = '';

    /**
     * @var string
     */
    protected $alternativText = '';

    /**
     * @var string
     */
    protected $legacyId = '';

    /**
     * @var int
     */
    protected $positionDarstellung = 0;

    /**
     * @var \DateTime
     */
    protected $modifyDate;

    /**
     * @var \DateTime
     */
    protected $createDate;

    /**
     * @var bool
     */
    protected $broken = false;

    /**
     * @var bool
     */
    protected $unused = false;

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
        $this->mandant = (string)$mandant;
    }

    /**
     * Returns the kennzeichenAnzeigeNeuesFenster
     *
     * @return bool $kennzeichenAnzeigeNeuesFenster
     */
    public function getKennzeichenAnzeigeNeuesFenster()
    {
        return $this->kennzeichenAnzeigeNeuesFenster;
    }

    /**
     * Sets the kennzeichenAnzeigeNeuesFenster
     *
     * @param bool $kennzeichenAnzeigeNeuesFenster
     *
     * @return void
     */
    public function setKennzeichenAnzeigeNeuesFenster($kennzeichenAnzeigeNeuesFenster)
    {
        $this->kennzeichenAnzeigeNeuesFenster = (bool)$kennzeichenAnzeigeNeuesFenster;
    }

    /**
     * Returns the uri
     *
     * @return string $uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the uri
     *
     * @param string $uri
     *
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = (string)$uri;
    }

    /**
     * Returns the titel
     *
     * @return string $titel
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Sets the titel
     *
     * @param string $titel
     *
     * @return void
     */
    public function setTitel($titel)
    {
        $this->titel = (string)$titel;
    }

    /**
     * Returns the beschreibung
     *
     * @return string $beschreibung
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets the beschreibung
     *
     * @param string $beschreibung
     *
     * @return void
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = (string)$beschreibung;
    }

    /**
     * Returns the alternativText
     *
     * @return string $alternativText
     */
    public function getAlternativText()
    {
        return $this->alternativText;
    }

    /**
     * Sets the alternativText
     *
     * @param string $alternativText
     *
     * @return void
     */
    public function setAlternativText($alternativText)
    {
        $this->alternativText = (string)$alternativText;
    }

    /**
     * Returns the legacyId
     *
     * @return string $legacyId
     */
    public function getLegacyId()
    {
        return $this->legacyId;
    }

    /**
     * Sets the legacyId
     *
     * @param string $legacyId
     *
     * @return void
     */
    public function setLegacyId($legacyId)
    {
        $this->legacyId = (string)$legacyId;
    }

    /**
     * Returns the positionDarstellung
     *
     * @return string $positionDarstellung
     */
    public function getPositionDarstellung()
    {
        return $this->positionDarstellung;
    }

    /**
     * Sets the positionDarstellung
     *
     * @param string $positionDarstellung
     *
     * @return void
     */
    public function setPositionDarstellung($positionDarstellung)
    {
        $this->positionDarstellung = (string)$positionDarstellung;
    }

    /**
     * Returns the modifyDate
     *
     * @return \DateTime $modifyDate
     */
    public function getModifyDate()
    {
        return $this->modifyDate;
    }

    /**
     * Sets the modifyDate
     *
     * @param \DateTime $modifyDate
     *
     * @return void
     */
    public function setModifyDate(\DateTime $modifyDate = null)
    {
        $this->modifyDate = $modifyDate;
    }

    /**
     * Returns the createDate
     *
     * @return \DateTime $createDate
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets the createDate
     *
     * @param \DateTime $createDate
     *
     * @return void
     */
    public function setCreateDate(\DateTime $createDate = null)
    {
        $this->createDate = $createDate;
    }

    /**
     * Returns the broken
     *
     * @return bool $broken
     */
    public function getBroken()
    {
        return $this->broken;
    }

    /**
     * Sets the broken
     *
     * @param bool $broken
     *
     * @return void
     */
    public function setBroken($broken)
    {
        $this->broken = (bool)$broken;
    }

    /**
     * Returns the unused
     *
     * @return bool $unused
     */
    public function getUnused()
    {
        return $this->unused;
    }

    /**
     * Sets the unused
     *
     * @param bool $unused
     *
     * @return void
     */
    public function setUnused($unused)
    {
        $this->unused = (bool)$unused;
    }
}
