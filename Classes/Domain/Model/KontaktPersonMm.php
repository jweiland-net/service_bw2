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
class KontaktPersonMm extends AbstractEntity
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
    protected $organisationsEinheitId = '';

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\KontaktPerson
     */
    protected $kontaktPerson;

    /**
     * @var int
     */
    protected $kontaktPersonId = 0;

    /**
     * @var int
     */
    protected $reihenfolge = 0;

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
     * Returns the organisationsEinheitId
     *
     * @return int $organisationsEinheitId
     */
    public function getOrganisationsEinheitId()
    {
        return $this->organisationsEinheitId;
    }

    /**
     * Sets the organisationsEinheitId
     *
     * @param int $organisationsEinheitId
     *
     * @return void
     */
    public function setOrganisationsEinheitId($organisationsEinheitId)
    {
        $this->organisationsEinheitId = (int)$organisationsEinheitId;
    }

    /**
     * Returns the kontaktPerson
     *
     * @return KontaktPerson $kontaktPerson
     */
    public function getKontaktPerson()
    {
        return $this->kontaktPerson;
    }

    /**
     * Sets the kontaktPerson
     *
     * @param KontaktPerson $kontaktPerson
     *
     * @return void
     */
    public function setKontaktPerson(KontaktPerson $kontaktPerson = null)
    {
        $this->kontaktPerson = $kontaktPerson;
    }

    /**
     * Returns the kontaktPersonId
     *
     * @return int $kontaktPersonId
     */
    public function getKontaktPersonId()
    {
        return $this->kontaktPersonId;
    }

    /**
     * Sets the kontaktPersonId
     *
     * @param int $kontaktPersonId
     *
     * @return void
     */
    public function setKontaktPersonId($kontaktPersonId)
    {
        $this->kontaktPersonId = (int)$kontaktPersonId;
    }

    /**
     * Returns the reihenfolge
     *
     * @return int $reihenfolge
     */
    public function getReihenfolge()
    {
        return $this->reihenfolge;
    }

    /**
     * Sets the reihenfolge
     *
     * @param int $reihenfolge
     *
     * @return void
     */
    public function setReihenfolge($reihenfolge)
    {
        $this->reihenfolge = (int)$reihenfolge;
    }
}
