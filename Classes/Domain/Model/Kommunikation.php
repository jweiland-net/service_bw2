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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Kommunikation extends AbstractEntity
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
    protected $kanal = '';

    /**
     * @var int
     */
    protected $reihenfolge = 0;

    /**
     * @var string
     */
    protected $kennung = '';

    /**
     * @var string
     */
    protected $kennungszusatz = '';

    /**
     * @var string
     */
    protected $zusatz = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * @var bool
     */
    protected $oeffentlich = false;

    /**
     * Kommunikation constructor.
     */
    public function __construct()
    {
        $this->gueltigkeiten = new ObjectStorage();
    }

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
     * Returns the kanal
     *
     * @return string $kanal
     */
    public function getKanal()
    {
        return $this->kanal;
    }

    /**
     * Sets the kanal
     *
     * @param string $kanal
     *
     * @return void
     */
    public function setKanal($kanal)
    {
        $this->kanal = (string)$kanal;
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

    /**
     * Returns the kennung
     *
     * @return string $kennung
     */
    public function getKennung()
    {
        return $this->kennung;
    }

    /**
     * Sets the kennung
     *
     * @param string $kennung
     *
     * @return void
     */
    public function setKennung($kennung)
    {
        $this->kennung = (string)$kennung;
    }

    /**
     * Returns the kennungszusatz
     *
     * @return string $kennungszusatz
     */
    public function getKennungszusatz()
    {
        return $this->kennungszusatz;
    }

    /**
     * Sets the kennungszusatz
     *
     * @param string $kennungszusatz
     *
     * @return void
     */
    public function setKennungszusatz($kennungszusatz)
    {
        $this->kennungszusatz = (string)$kennungszusatz;
    }

    /**
     * Returns the zusatz
     *
     * @return string $zusatz
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * Sets the zusatz
     *
     * @param string $zusatz
     *
     * @return void
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = (string)$zusatz;
    }

    /**
     * Returns the gueltigkeiten
     *
     * @return ObjectStorage $gueltigkeiten
     */
    public function getGueltigkeiten()
    {
        return $this->gueltigkeiten;
    }

    /**
     * Sets the gueltigkeiten
     *
     * @param ObjectStorage $gueltigkeiten
     *
     * @return void
     */
    public function setGueltigkeiten(ObjectStorage $gueltigkeiten)
    {
        $this->gueltigkeiten = $gueltigkeiten;
    }

    /**
     * Returns the oeffentlich
     *
     * @return bool $oeffentlich
     */
    public function getOeffentlich()
    {
        return $this->oeffentlich;
    }

    /**
     * Sets the oeffentlich
     *
     * @param bool $oeffentlich
     *
     * @return void
     */
    public function setOeffentlich($oeffentlich)
    {
        $this->oeffentlich = (bool)$oeffentlich;
    }
}
