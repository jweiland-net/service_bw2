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
class Benutzer extends AbstractEntity
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
    protected $benutzername = '';

    /**
     * @var string
     */
    protected $vorname = '';

    /**
     * @var string
     */
    protected $nachname = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $idp = '';

    /**
     * @var string
     */
    protected $idpId = '';

    /**
     * @var string
     */
    protected $legacyId = '';

    /**
     * @var \DateTime
     */
    protected $modifyDate;

    /**
     * @var \DateTime
     */
    protected $createDate;

    /**
     * @var \DateTime
     */
    protected $featureAcceptedDate;

    /**
     * @var string
     */
    protected $benutzergruppenString = '';

    /**
     * @var string
     */
    protected $fullname = '';

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
     * Returns the benutzername
     *
     * @return string $benutzername
     */
    public function getBenutzername()
    {
        return $this->benutzername;
    }

    /**
     * Sets the benutzername
     *
     * @param string $benutzername
     *
     * @return void
     */
    public function setBenutzername($benutzername)
    {
        $this->benutzername = (string)$benutzername;
    }

    /**
     * Returns the vorname
     *
     * @return string $vorname
     */
    public function getVorname()
    {
        return $this->vorname;
    }

    /**
     * Sets the vorname
     *
     * @param string $vorname
     *
     * @return void
     */
    public function setVorname($vorname)
    {
        $this->vorname = (string)$vorname;
    }

    /**
     * Returns the nachname
     *
     * @return string $nachname
     */
    public function getNachname()
    {
        return $this->nachname;
    }

    /**
     * Sets the nachname
     *
     * @param string $nachname
     *
     * @return void
     */
    public function setNachname($nachname)
    {
        $this->nachname = (string)$nachname;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
    }

    /**
     * Returns the idp
     *
     * @return string $idp
     */
    public function getIdp()
    {
        return $this->idp;
    }

    /**
     * Sets the idp
     *
     * @param string $idp
     *
     * @return void
     */
    public function setIdp($idp)
    {
        $this->idp = (string)$idp;
    }

    /**
     * Returns the idpId
     *
     * @return string $idpId
     */
    public function getIdpId()
    {
        return $this->idpId;
    }

    /**
     * Sets the idpId
     *
     * @param string $idpId
     *
     * @return void
     */
    public function setIdpId($idpId)
    {
        $this->idpId = (string)$idpId;
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
     * Returns the featureAcceptedDate
     *
     * @return \DateTime $featureAcceptedDate
     */
    public function getFeatureAcceptedDate()
    {
        return $this->featureAcceptedDate;
    }

    /**
     * Sets the featureAcceptedDate
     *
     * @param \DateTime $featureAcceptedDate
     *
     * @return void
     */
    public function setFeatureAcceptedDate(\DateTime $featureAcceptedDate = null)
    {
        $this->featureAcceptedDate = $featureAcceptedDate;
    }

    /**
     * Returns the benutzergruppenString
     *
     * @return string $benutzergruppenString
     */
    public function getBenutzergruppenString()
    {
        return $this->benutzergruppenString;
    }

    /**
     * Sets the benutzergruppenString
     *
     * @param string $benutzergruppenString
     *
     * @return void
     */
    public function setBenutzergruppenString($benutzergruppenString)
    {
        $this->benutzergruppenString = (string)$benutzergruppenString;
    }

    /**
     * Returns the fullname
     *
     * @return string $fullname
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Sets the fullname
     *
     * @param string $fullname
     *
     * @return void
     */
    public function setFullname($fullname)
    {
        $this->fullname = (string)$fullname;
    }
}
