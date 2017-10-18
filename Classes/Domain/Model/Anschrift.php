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
class Anschrift extends AbstractEntity
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
    protected $type = '';

    /**
     * @var string
     */
    protected $anfahrtskizzeAssetId = '';

    /**
     * @var string
     */
    protected $anfahrtskizzeAssetUrl = '';

    /**
     * @var string
     */
    protected $strasse = '';

    /**
     * @var string
     */
    protected $hausnummer = '';

    /**
     * @var string
     */
    protected $postleitzahl = '';

    /**
     * @var string
     */
    protected $postfach = '';

    /**
     * @var string
     */
    protected $ort = '';

    /**
     * @var string
     */
    protected $ortsteil = '';

    /**
     * @var string
     */
    protected $zusatz = '';

    /**
     * @var bool
     */
    protected $kennzeichenAufzug = false;

    /**
     * @var bool
     */
    protected $kennzeichenRollstuhlgerecht = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\GeoKodierung>
     */
    protected $geoKodierungen;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\VerwaltungspolitischeKodierung
     */
    protected $verwaltungspolitischeKodierung;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Kommunikation>
     */
    protected $kommunikationen;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\KontaktPersonen>
     */
    protected $kontaktPersonen;

    /**
     * Anschrift constructor.
     */
    public function __construct()
    {
        $this->geoKodierungen = new ObjectStorage();
        $this->gueltigkeiten = new ObjectStorage();
        $this->kommunikationen = new ObjectStorage();
        $this->kontaktPersonen = new ObjectStorage();
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
     * Returns the type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = (string)$type;
    }

    /**
     * Returns the anfahrtskizzeAssetId
     *
     * @return string $anfahrtskizzeAssetId
     */
    public function getAnfahrtskizzeAssetId()
    {
        return $this->anfahrtskizzeAssetId;
    }

    /**
     * Sets the anfahrtskizzeAssetId
     *
     * @param string $anfahrtskizzeAssetId
     *
     * @return void
     */
    public function setAnfahrtskizzeAssetId($anfahrtskizzeAssetId)
    {
        $this->anfahrtskizzeAssetId = (string)$anfahrtskizzeAssetId;
    }

    /**
     * Returns the anfahrtskizzeAssetUrl
     *
     * @return string $anfahrtskizzeAssetUrl
     */
    public function getAnfahrtskizzeAssetUrl()
    {
        return $this->anfahrtskizzeAssetUrl;
    }

    /**
     * Sets the anfahrtskizzeAssetUrl
     *
     * @param string $anfahrtskizzeAssetUrl
     *
     * @return void
     */
    public function setAnfahrtskizzeAssetUrl($anfahrtskizzeAssetUrl)
    {
        $this->anfahrtskizzeAssetUrl = (string)$anfahrtskizzeAssetUrl;
    }

    /**
     * Returns the strasse
     *
     * @return string $strasse
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * Sets the strasse
     *
     * @param string $strasse
     *
     * @return void
     */
    public function setStrasse($strasse)
    {
        $this->strasse = (string)$strasse;
    }

    /**
     * Returns the hausnummer
     *
     * @return string $hausnummer
     */
    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    /**
     * Sets the hausnummer
     *
     * @param string $hausnummer
     *
     * @return void
     */
    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = (string)$hausnummer;
    }

    /**
     * Returns the postleitzahl
     *
     * @return string $postleitzahl
     */
    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    /**
     * Sets the postleitzahl
     *
     * @param string $postleitzahl
     *
     * @return void
     */
    public function setPostleitzahl($postleitzahl)
    {
        $this->postleitzahl = (string)$postleitzahl;
    }

    /**
     * Returns the postfach
     *
     * @return string $postfach
     */
    public function getPostfach()
    {
        return $this->postfach;
    }

    /**
     * Sets the postfach
     *
     * @param string $postfach
     *
     * @return void
     */
    public function setPostfach($postfach)
    {
        $this->postfach = (string)$postfach;
    }

    /**
     * Returns the ort
     *
     * @return string $ort
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Sets the ort
     *
     * @param string $ort
     *
     * @return void
     */
    public function setOrt($ort)
    {
        $this->ort = (string)$ort;
    }

    /**
     * Returns the ortsteil
     *
     * @return string $ortsteil
     */
    public function getOrtsteil()
    {
        return $this->ortsteil;
    }

    /**
     * Sets the ortsteil
     *
     * @param string $ortsteil
     *
     * @return void
     */
    public function setOrtsteil($ortsteil)
    {
        $this->ortsteil = (string)$ortsteil;
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
     * Returns the kennzeichenAufzug
     *
     * @return bool $kennzeichenAufzug
     */
    public function getKennzeichenAufzug()
    {
        return $this->kennzeichenAufzug;
    }

    /**
     * Sets the kennzeichenAufzug
     *
     * @param bool $kennzeichenAufzug
     *
     * @return void
     */
    public function setKennzeichenAufzug($kennzeichenAufzug)
    {
        $this->kennzeichenAufzug = (bool)$kennzeichenAufzug;
    }

    /**
     * Returns the kennzeichenRollstuhlgerecht
     *
     * @return bool $kennzeichenRollstuhlgerecht
     */
    public function getKennzeichenRollstuhlgerecht()
    {
        return $this->kennzeichenRollstuhlgerecht;
    }

    /**
     * Sets the kennzeichenRollstuhlgerecht
     *
     * @param bool $kennzeichenRollstuhlgerecht
     *
     * @return void
     */
    public function setKennzeichenRollstuhlgerecht($kennzeichenRollstuhlgerecht)
    {
        $this->kennzeichenRollstuhlgerecht = (bool)$kennzeichenRollstuhlgerecht;
    }

    /**
     * Returns the geoKodierungen
     *
     * @return ObjectStorage $geoKodierungen
     */
    public function getGeoKodierungen()
    {
        return $this->geoKodierungen;
    }

    /**
     * Sets the geoKodierungen
     *
     * @param ObjectStorage $geoKodierungen
     *
     * @return void
     */
    public function setGeoKodierungen(ObjectStorage $geoKodierungen)
    {
        $this->geoKodierungen = $geoKodierungen;
    }

    /**
     * Returns the verwaltungspolitischeKodierung
     *
     * @return VerwaltungspolitischeKodierung $verwaltungspolitischeKodierung
     */
    public function getVerwaltungspolitischeKodierung()
    {
        return $this->verwaltungspolitischeKodierung;
    }

    /**
     * Sets the verwaltungspolitischeKodierung
     *
     * @param VerwaltungspolitischeKodierung $verwaltungspolitischeKodierung
     *
     * @return void
     */
    public function setVerwaltungspolitischeKodierung(VerwaltungspolitischeKodierung $verwaltungspolitischeKodierung = null)
    {
        $this->verwaltungspolitischeKodierung = $verwaltungspolitischeKodierung;
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
    public function setGueltigkeiten(ObjectStorage $gueltigkeiten = null)
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
     * Returns the kontaktPersonen
     *
     * @return ObjectStorage $kontaktPersonen
     */
    public function getKontaktPersonen()
    {
        return $this->kontaktPersonen;
    }

    /**
     * Sets the kontaktPersonen
     *
     * @param ObjectStorage $kontaktPersonen
     *
     * @return void
     */
    public function setKontaktPersonen(ObjectStorage $kontaktPersonen)
    {
        $this->kontaktPersonen = $kontaktPersonen;
    }
}
