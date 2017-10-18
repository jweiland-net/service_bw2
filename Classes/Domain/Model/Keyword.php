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
class Keyword extends AbstractEntity
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
     * @var string
     */
    protected $name = '';

    /**
     * @var bool
     */
    protected $visiblePortal = false;

    /**
     * @var int
     */
    protected $legacyId = 0;

    /**
     * @var string
     */
    protected $legacyType = '';

    /**
     * @var string
     */
    protected $verwendung = '';

    /**
     * @var int
     */
    protected $modifyDate = 0;

    /**
     * @var int
     */
    protected $createDate = 0;

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
     * Returns the visiblePortal
     *
     * @return bool $visiblePortal
     */
    public function getVisiblePortal()
    {
        return $this->visiblePortal;
    }

    /**
     * Sets the visiblePortal
     *
     * @param bool $visiblePortal
     *
     * @return void
     */
    public function setVisiblePortal($visiblePortal)
    {
        $this->visiblePortal = (bool)$visiblePortal;
    }

    /**
     * Returns the legacyId
     *
     * @return int $legacyId
     */
    public function getLegacyId()
    {
        return $this->legacyId;
    }

    /**
     * Sets the legacyId
     *
     * @param int $legacyId
     *
     * @return void
     */
    public function setLegacyId($legacyId)
    {
        $this->legacyId = (int)$legacyId;
    }

    /**
     * Returns the legacyType
     *
     * @return string $legacyType
     */
    public function getLegacyType()
    {
        return $this->legacyType;
    }

    /**
     * Sets the legacyType
     *
     * @param string $legacyType
     *
     * @return void
     */
    public function setLegacyType($legacyType)
    {
        $this->legacyType = (string)$legacyType;
    }

    /**
     * Returns the verwendung
     *
     * @return string $verwendung
     */
    public function getVerwendung()
    {
        return $this->verwendung;
    }

    /**
     * Sets the verwendung
     *
     * @param string $verwendung
     *
     * @return void
     */
    public function setVerwendung($verwendung)
    {
        $this->verwendung = (string)$verwendung;
    }

    /**
     * Returns the modifyDate
     *
     * @return int $modifyDate
     */
    public function getModifyDate()
    {
        return $this->modifyDate;
    }

    /**
     * Sets the modifyDate
     *
     * @param int $modifyDate
     *
     * @return void
     */
    public function setModifyDate($modifyDate)
    {
        $this->modifyDate = (int)$modifyDate;
    }

    /**
     * Returns the createDate
     *
     * @return int $createDate
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets the createDate
     *
     * @param int $createDate
     *
     * @return void
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = (int)$createDate;
    }
}
