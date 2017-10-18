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
class OeffnungszeitenMm extends AbstractEntity
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
    protected $typ = '';

    /**
     * @var string
     */
    protected $hinweisText = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Oeffnungszeiten>
     */
    protected $regulaereZeiten;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Oeffnungszeiten>
     */
    protected $abweichendeZeiten;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * OeffnungszeitenMm constructor.
     */
    public function __construct()
    {
        $this->regulaereZeiten = new ObjectStorage();
        $this->abweichendeZeiten = new ObjectStorage();
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
     * Returns the typ
     *
     * @return string $typ
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * Sets the typ
     *
     * @param string $typ
     *
     * @return void
     */
    public function setTyp($typ)
    {
        $this->typ = (string)$typ;
    }

    /**
     * Returns the hinweisText
     *
     * @return string $hinweisText
     */
    public function getHinweisText()
    {
        return $this->hinweisText;
    }

    /**
     * Sets the hinweisText
     *
     * @param string $hinweisText
     *
     * @return void
     */
    public function setHinweisText($hinweisText)
    {
        $this->hinweisText = (string)$hinweisText;
    }

    /**
     * Returns the regulaereZeiten
     *
     * @return ObjectStorage $regulaereZeiten
     */
    public function getRegulaereZeiten()
    {
        return $this->regulaereZeiten;
    }

    /**
     * Sets the regulaereZeiten
     *
     * @param ObjectStorage $regulaereZeiten
     *
     * @return void
     */
    public function setRegulaereZeiten(ObjectStorage $regulaereZeiten)
    {
        $this->regulaereZeiten = $regulaereZeiten;
    }

    /**
     * Returns the abweichendeZeiten
     *
     * @return ObjectStorage $abweichendeZeiten
     */
    public function getAbweichendeZeiten()
    {
        return $this->abweichendeZeiten;
    }

    /**
     * Sets the abweichendeZeiten
     *
     * @param ObjectStorage $abweichendeZeiten
     *
     * @return void
     */
    public function setAbweichendeZeiten(ObjectStorage $abweichendeZeiten)
    {
        $this->abweichendeZeiten = $abweichendeZeiten;
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
}
