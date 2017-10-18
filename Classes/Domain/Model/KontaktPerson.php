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
class KontaktPerson extends AbstractEntity
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
    protected $anrede = '';

    /**
     * @var string
     */
    protected $sprechzeiten = '';

    /**
     * @var string
     */
    protected $titel = '';

    /**
     * @var string
     */
    protected $position = '';

    /**
     * @var string
     */
    protected $rolle = '';

    /**
     * @var string
     */
    protected $infotext = '';

    /**
     * @var string
     */
    protected $vorname = '';

    /**
     * @var string
     */
    protected $familienname = '';

    /**
     * @var string
     */
    protected $raum = '';

    /**
     * @var string
     */
    protected $gebaeude = '';

    /**
     * @var string
     */
    protected $fotoAssetId = '';

    /**
     * @var string
     */
    protected $fotoAssetUrl = '';

    /**
     * @var string
     */
    protected $fotoAssetAltDe = '';

    /**
     * @var string
     */
    protected $fotoAssetAltEn = '';

    /**
     * @var string
     */
    protected $fotoAssetAltFr = '';

    /**
     * @var int
     */
    protected $reihenfolge = 0;

    /**
     * @var int
     */
    protected $legacyId = 0;

    /**
     * @var bool
     */
    protected $hasLeitungsfunktion = false;

    /**
     * @var bool
     */
    protected $isPublishedInMaList = false;

    /**
     * @var bool
     */
    protected $isPublishedInPortal = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Anschrift>
     */
    protected $anschriften;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Zustaendigkeit>
     */
    protected $zustaendigkeiten;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Kommunikation>
     */
    protected $kommunikationen;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\InternetAdresse>
     */
    protected $internetAdressen;

    /**
     * KontaktPerson constructor.
     */
    public function __construct()
    {
        $this->anschriften = new ObjectStorage();
        $this->zustaendigkeiten = new ObjectStorage();
        $this->gueltigkeiten = new ObjectStorage();
        $this->kommunikationen = new ObjectStorage();
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
     * Returns the anrede
     *
     * @return string $anrede
     */
    public function getAnrede()
    {
        return $this->anrede;
    }

    /**
     * Sets the anrede
     *
     * @param string $anrede
     *
     * @return void
     */
    public function setAnrede($anrede)
    {
        $this->anrede = (string)$anrede;
    }

    /**
     * Returns the sprechzeiten
     *
     * @return string $sprechzeiten
     */
    public function getSprechzeiten()
    {
        return $this->sprechzeiten;
    }

    /**
     * Sets the sprechzeiten
     *
     * @param string $sprechzeiten
     *
     * @return void
     */
    public function setSprechzeiten($sprechzeiten)
    {
        $this->sprechzeiten = (string)$sprechzeiten;
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
     * Returns the position
     *
     * @return string $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position
     *
     * @param string $position
     *
     * @return void
     */
    public function setPosition($position)
    {
        $this->position = (string)$position;
    }

    /**
     * Returns the rolle
     *
     * @return string $rolle
     */
    public function getRolle()
    {
        return $this->rolle;
    }

    /**
     * Sets the rolle
     *
     * @param string $rolle
     *
     * @return void
     */
    public function setRolle($rolle)
    {
        $this->rolle = (string)$rolle;
    }

    /**
     * Returns the infotext
     *
     * @return string $infotext
     */
    public function getInfotext()
    {
        return $this->infotext;
    }

    /**
     * Sets the infotext
     *
     * @param string $infotext
     *
     * @return void
     */
    public function setInfotext($infotext)
    {
        $this->infotext = (string)$infotext;
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
     * Returns the familienname
     *
     * @return string $familienname
     */
    public function getFamilienname()
    {
        return $this->familienname;
    }

    /**
     * Sets the familienname
     *
     * @param string $familienname
     *
     * @return void
     */
    public function setFamilienname($familienname)
    {
        $this->familienname = (string)$familienname;
    }

    /**
     * Returns the raum
     *
     * @return string $raum
     */
    public function getRaum()
    {
        return $this->raum;
    }

    /**
     * Sets the raum
     *
     * @param string $raum
     *
     * @return void
     */
    public function setRaum($raum)
    {
        $this->raum = (string)$raum;
    }

    /**
     * Returns the gebaeude
     *
     * @return string $gebaeude
     */
    public function getGebaeude()
    {
        return $this->gebaeude;
    }

    /**
     * Sets the gebaeude
     *
     * @param string $gebaeude
     *
     * @return void
     */
    public function setGebaeude($gebaeude)
    {
        $this->gebaeude = (string)$gebaeude;
    }

    /**
     * Returns the fotoAssetId
     *
     * @return string $fotoAssetId
     */
    public function getFotoAssetId()
    {
        return $this->fotoAssetId;
    }

    /**
     * Sets the fotoAssetId
     *
     * @param string $fotoAssetId
     *
     * @return void
     */
    public function setFotoAssetId($fotoAssetId)
    {
        $this->fotoAssetId = (string)$fotoAssetId;
    }

    /**
     * Returns the fotoAssetUrl
     *
     * @return string $fotoAssetUrl
     */
    public function getFotoAssetUrl()
    {
        return $this->fotoAssetUrl;
    }

    /**
     * Sets the fotoAssetUrl
     *
     * @param string $fotoAssetUrl
     *
     * @return void
     */
    public function setFotoAssetUrl($fotoAssetUrl)
    {
        $this->fotoAssetUrl = (string)$fotoAssetUrl;
    }

    /**
     * Returns the fotoAssetAltDe
     *
     * @return string $fotoAssetAltDe
     */
    public function getFotoAssetAltDe()
    {
        return $this->fotoAssetAltDe;
    }

    /**
     * Sets the fotoAssetAltDe
     *
     * @param string $fotoAssetAltDe
     *
     * @return void
     */
    public function setFotoAssetAltDe($fotoAssetAltDe)
    {
        $this->fotoAssetAltDe = (string)$fotoAssetAltDe;
    }

    /**
     * Returns the fotoAssetAltEn
     *
     * @return string $fotoAssetAltEn
     */
    public function getFotoAssetAltEn()
    {
        return $this->fotoAssetAltEn;
    }

    /**
     * Sets the fotoAssetAltEn
     *
     * @param string $fotoAssetAltEn
     *
     * @return void
     */
    public function setFotoAssetAltEn($fotoAssetAltEn)
    {
        $this->fotoAssetAltEn = (string)$fotoAssetAltEn;
    }

    /**
     * Returns the fotoAssetAltFr
     *
     * @return string $fotoAssetAltFr
     */
    public function getFotoAssetAltFr()
    {
        return $this->fotoAssetAltFr;
    }

    /**
     * Sets the fotoAssetAltFr
     *
     * @param string $fotoAssetAltFr
     *
     * @return void
     */
    public function setFotoAssetAltFr($fotoAssetAltFr)
    {
        $this->fotoAssetAltFr = (string)$fotoAssetAltFr;
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
     * Returns the hasLeitungsfunktion
     *
     * @return bool $hasLeitungsfunktion
     */
    public function getHasLeitungsfunktion()
    {
        return $this->hasLeitungsfunktion;
    }

    /**
     * Sets the hasLeitungsfunktion
     *
     * @param bool $hasLeitungsfunktion
     *
     * @return void
     */
    public function setHasLeitungsfunktion($hasLeitungsfunktion)
    {
        $this->hasLeitungsfunktion = (bool)$hasLeitungsfunktion;
    }

    /**
     * Returns the isPublishedInMaList
     *
     * @return bool $isPublishedInMaList
     */
    public function getIsPublishedInMaList()
    {
        return $this->isPublishedInMaList;
    }

    /**
     * Sets the isPublishedInMaList
     *
     * @param bool $isPublishedInMaList
     *
     * @return void
     */
    public function setIsPublishedInMaList($isPublishedInMaList)
    {
        $this->isPublishedInMaList = (bool)$isPublishedInMaList;
    }

    /**
     * Returns the isPublishedInPortal
     *
     * @return bool $isPublishedInPortal
     */
    public function getIsPublishedInPortal()
    {
        return $this->isPublishedInPortal;
    }

    /**
     * Sets the isPublishedInPortal
     *
     * @param bool $isPublishedInPortal
     *
     * @return void
     */
    public function setIsPublishedInPortal($isPublishedInPortal)
    {
        $this->isPublishedInPortal = (bool)$isPublishedInPortal;
    }

    /**
     * Returns the anschriften
     *
     * @return ObjectStorage $anschriften
     */
    public function getAnschriften()
    {
        return $this->anschriften;
    }

    /**
     * Sets the anschriften
     *
     * @param ObjectStorage $anschriften
     *
     * @return void
     */
    public function setAnschriften(ObjectStorage $anschriften)
    {
        $this->anschriften = $anschriften;
    }

    /**
     * Returns the zustaendigkeiten
     *
     * @return ObjectStorage $zustaendigkeiten
     */
    public function getZustaendigkeiten()
    {
        return $this->zustaendigkeiten;
    }

    /**
     * Sets the zustaendigkeiten
     *
     * @param ObjectStorage $zustaendigkeiten
     *
     * @return void
     */
    public function setZustaendigkeiten(ObjectStorage $zustaendigkeiten)
    {
        $this->zustaendigkeiten = $zustaendigkeiten;
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
     * Returns the kommunikationen
     *
     * @return ObjectStorage $kommunikationen
     */
    public function getKommunikationen()
    {
        return $this->kommunikationen;
    }

    /**
     * Sets the kommunikationen
     *
     * @param ObjectStorage $kommunikationen
     *
     * @return void
     */
    public function setKommunikationen(ObjectStorage $kommunikationen)
    {
        $this->kommunikationen = $kommunikationen;
    }

    /**
     * Returns the internetAdressen
     *
     * @return ObjectStorage $internetAdressen
     */
    public function getInternetAdressen()
    {
        return $this->internetAdressen;
    }

    /**
     * Sets the internetAdressen
     *
     * @param ObjectStorage $internetAdressen
     *
     * @return void
     */
    public function setInternetAdressen(ObjectStorage $internetAdressen)
    {
        $this->internetAdressen = $internetAdressen;
    }
}
