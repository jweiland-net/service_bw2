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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrganisationsEinheit extends AbstractEntity
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
     * @var string
     */
    protected $kurzBeschreibung = '';

    /**
     * @var string
     */
    protected $infoOeffnungszeitenText = '';

    /**
     * @var int
     */
    protected $regionId = 0;

    /**
     * @var array
     */
    protected $oeBehoerdengruppen = [];

    /**
     * @var array
     */
    protected $assignedBehoerdenGruppen = [];

    /**
     * @var string
     */
    protected $pfad = '';

    /**
     * @var int
     */
    protected $legacyId = 0;

    /**
     * @var int
     */
    protected $parentId = 0;

    /**
     * @var string
     */
    protected $assetId = '';

    /**
     * @var string
     */
    protected $assetUrl = '';

    /**
     * @var string
     */
    protected $assetAltTextDe = '';

    /**
     * @var string
     */
    protected $assetAltTextFr = '';

    /**
     * @var string
     */
    protected $assetAltTextEn = '';

    /**
     * @var array
     */
    protected $uebergeordnet = [];

    /**
     * @var bool
     */
    protected $behoerde = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Benutzer>
     */
    protected $benutzer;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\OeffnungszeitenMm>
     */
    protected $infoOeffnungszeitenStrukturiert;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Anschrift>
     */
    protected $anschriften;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Kommunikation>
     */
    protected $kommunikationen;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\KontaktPerson>
     */
    protected $kontaktPersonen;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Kommunikation>
     */
    protected $kommunikationsSysteme;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\BankVerbindung>
     */
    protected $bankVerbindungen;

    /**
     * @var string
     */
    protected $behoerdenschluessel = '';

    /**
     * @var string
     */
    protected $glaeubigerIdentifikationsNummer = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Info115
     */
    protected $info115;

    /**
     * @var string
     */
    protected $publishStatus = '';

    /**
     * @var \DateTime
     */
    protected $publishDate;

    /**
     * @var string
     */
    protected $publishedVersion = '';

    /**
     * @var int
     */
    protected $version = 0;

    /**
     * @var \DateTime
     */
    protected $modifyDate;

    /**
     * @var \DateTime
     */
    protected $createDate;

    /**
     * @var string
     */
    protected $createdBy = '';

    /**
     * @var string
     */
    protected $createdByMandant = '';

    /**
     * @var string
     */
    protected $modifiedBy = '';

    /**
     * @var string
     */
    protected $modifiedByMandant = '';

    /**
     * @var \DateTime
     */
    protected $releaseDate;

    /**
     * @var \DateTime
     */
    protected $lastPublishedReleaseDate;

    /**
     * @var \JWeiland\ServiceBw2\Domain\Model\Behoerde
     */
    protected $zugehoerigeBehoerde;

    /**
     * Constructor
     */
    public function __contruct()
    {
        $this->benutzer = new ObjectStorage();
        $this->infoOeffnungszeitenStrukturiert = new ObjectStorage();
        $this->anschriften = new ObjectStorage();
        $this->kommunikationen = new ObjectStorage();
        $this->kontaktPersonen = new ObjectStorage();
        $this->kommunikationsSysteme = new ObjectStorage();
        $this->bankVerbindungen = new ObjectStorage();
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
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Returns the kurzBeschreibung
     *
     * @return string $kurzBeschreibung
     */
    public function getKurzBeschreibung()
    {
        return $this->kurzBeschreibung;
    }

    /**
     * Sets the kurzBeschreibung
     *
     * @param string $kurzBeschreibung
     *
     * @return void
     */
    public function setKurzBeschreibung($kurzBeschreibung)
    {
        $this->kurzBeschreibung = (string)$kurzBeschreibung;
    }

    /**
     * Returns the infoOeffnungszeitenText
     *
     * @return string $infoOeffnungszeitenText
     */
    public function getInfoOeffnungszeitenText()
    {
        return $this->infoOeffnungszeitenText;
    }

    /**
     * Sets the infoOeffnungszeitenText
     *
     * @param string $infoOeffnungszeitenText
     *
     * @return void
     */
    public function setInfoOeffnungszeitenText($infoOeffnungszeitenText)
    {
        $this->infoOeffnungszeitenText = (string)$infoOeffnungszeitenText;
    }

    /**
     * Returns the regionId
     *
     * @return int $regionId
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * Sets the regionId
     *
     * @param int $regionId
     *
     * @return void
     */
    public function setRegionId($regionId)
    {
        $this->regionId = (int)$regionId;
    }

    /**
     * Returns the oeBehoerdengruppen
     *
     * @return array $oeBehoerdengruppen
     */
    public function getOeBehoerdengruppen()
    {
        return $this->oeBehoerdengruppen;
    }

    /**
     * Sets the oeBehoerdengruppen
     *
     * @param array $oeBehoerdengruppen
     *
     * @return void
     */
    public function setOeBehoerdengruppen($oeBehoerdengruppen)
    {
        $this->oeBehoerdengruppen = (array)$oeBehoerdengruppen;
    }

    /**
     * Returns the assignedBehoerdenGruppen
     *
     * @return array $assignedBehoerdenGruppen
     */
    public function getAssignedBehoerdenGruppen()
    {
        return $this->assignedBehoerdenGruppen;
    }

    /**
     * Sets the assignedBehoerdenGruppen
     *
     * @param array $assignedBehoerdenGruppen
     *
     * @return void
     */
    public function setAssignedBehoerdenGruppen($assignedBehoerdenGruppen)
    {
        $this->assignedBehoerdenGruppen = (array)$assignedBehoerdenGruppen;
    }

    /**
     * Returns the pfad
     *
     * @return string $pfad
     */
    public function getPfad()
    {
        return $this->pfad;
    }

    /**
     * Sets the pfad
     *
     * @param string $pfad
     *
     * @return void
     */
    public function setPfad($pfad)
    {
        $this->pfad = (string)$pfad;
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
     * Returns the parentId
     *
     * @return int $parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the parentId
     *
     * @param int $parentId
     *
     * @return void
     */
    public function setParentId($parentId)
    {
        $this->parentId = (int)$parentId;
    }

    /**
     * Returns the assetId
     *
     * @return string $assetId
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * Sets the assetId
     *
     * @param string $assetId
     *
     * @return void
     */
    public function setAssetId($assetId)
    {
        $this->assetId = (string)$assetId;
    }

    /**
     * Returns the assetUrl
     *
     * @return string $assetUrl
     */
    public function getAssetUrl()
    {
        return $this->assetUrl;
    }

    /**
     * Sets the assetUrl
     *
     * @param string $assetUrl
     *
     * @return void
     */
    public function setAssetUrl($assetUrl)
    {
        $this->assetUrl = (string)$assetUrl;
    }

    /**
     * Returns the assetAltTextDe
     *
     * @return string $assetAltTextDe
     */
    public function getAssetAltTextDe()
    {
        return $this->assetAltTextDe;
    }

    /**
     * Sets the assetAltTextDe
     *
     * @param string $assetAltTextDe
     *
     * @return void
     */
    public function setAssetAltTextDe($assetAltTextDe)
    {
        $this->assetAltTextDe = (string)$assetAltTextDe;
    }

    /**
     * Returns the assetAltTextFr
     *
     * @return string $assetAltTextFr
     */
    public function getAssetAltTextFr()
    {
        return $this->assetAltTextFr;
    }

    /**
     * Sets the assetAltTextFr
     *
     * @param string $assetAltTextFr
     *
     * @return void
     */
    public function setAssetAltTextFr($assetAltTextFr)
    {
        $this->assetAltTextFr = (string)$assetAltTextFr;
    }

    /**
     * Returns the assetAltTextEn
     *
     * @return string $assetAltTextEn
     */
    public function getAssetAltTextEn()
    {
        return $this->assetAltTextEn;
    }

    /**
     * Sets the assetAltTextEn
     *
     * @param string $assetAltTextEn
     *
     * @return void
     */
    public function setAssetAltTextEn($assetAltTextEn)
    {
        $this->assetAltTextEn = (string)$assetAltTextEn;
    }

    /**
     * Returns the uebergeordnet
     *
     * @return array $uebergeordnet
     */
    public function getUebergeordnet()
    {
        return $this->uebergeordnet;
    }

    /**
     * Sets the uebergeordnet
     *
     * @param array $uebergeordnet
     *
     * @return void
     */
    public function setUebergeordnet($uebergeordnet)
    {
        $this->uebergeordnet = (array)$uebergeordnet;
    }

    /**
     * Returns is behoerde
     *
     * @return bool $behoerde
     */
    public function isBehoerde()
    {
        return $this->behoerde;
    }

    /**
     * Sets the behoerde
     *
     * @param bool $behoerde
     *
     * @return void
     */
    public function setBehoerde($behoerde)
    {
        $this->behoerde = (bool)$behoerde;
    }

    /**
     * Returns the benutzer
     *
     * @return ObjectStorage $benutzer
     */
    public function getBenutzer()
    {
        return $this->benutzer;
    }

    /**
     * Sets the benutzer
     *
     * @param ObjectStorage $benutzer
     *
     * @return void
     */
    public function setBenutzer(ObjectStorage $benutzer)
    {
        $this->benutzer = $benutzer;
    }

    /**
     * Returns the infoOeffnungszeitenStrukturiert
     *
     * @return ObjectStorage $infoOeffnungszeitenStrukturiert
     */
    public function getInfoOeffnungszeitenStrukturiert()
    {
        return $this->infoOeffnungszeitenStrukturiert;
    }

    /**
     * Sets the infoOeffnungszeitenStrukturiert
     *
     * @param ObjectStorage $infoOeffnungszeitenStrukturiert
     *
     * @return void
     */
    public function setInfoOeffnungszeitenStrukturiert(ObjectStorage $infoOeffnungszeitenStrukturiert)
    {
        $this->infoOeffnungszeitenStrukturiert = $infoOeffnungszeitenStrukturiert;
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

    /**
     * Returns the kommunikationsSysteme
     *
     * @return ObjectStorage $kommunikationsSysteme
     */
    public function getKommunikationsSysteme()
    {
        return $this->kommunikationsSysteme;
    }

    /**
     * Sets the kommunikationsSysteme
     *
     * @param ObjectStorage $kommunikationsSysteme
     *
     * @return void
     */
    public function setKommunikationsSysteme(ObjectStorage $kommunikationsSysteme)
    {
        $this->kommunikationsSysteme = $kommunikationsSysteme;
    }

    /**
     * Returns the bankVerbindungen
     *
     * @return ObjectStorage $bankVerbindungen
     */
    public function getBankVerbindungen()
    {
        return $this->bankVerbindungen;
    }

    /**
     * Sets the bankVerbindungen
     *
     * @param ObjectStorage $bankVerbindungen
     *
     * @return void
     */
    public function setBankVerbindungen(ObjectStorage $bankVerbindungen)
    {
        $this->bankVerbindungen = $bankVerbindungen;
    }

    /**
     * Returns the behoerdenschluessel
     *
     * @return string $behoerdenschluessel
     */
    public function getBehoerdenschluessel()
    {
        return $this->behoerdenschluessel;
    }

    /**
     * Sets the behoerdenschluessel
     *
     * @param string $behoerdenschluessel
     *
     * @return void
     */
    public function setBehoerdenschluessel($behoerdenschluessel)
    {
        $this->behoerdenschluessel = (string)$behoerdenschluessel;
    }

    /**
     * Returns the glaeubigerIdentifikationsNummer
     *
     * @return string $glaeubigerIdentifikationsNummer
     */
    public function getGlaeubigerIdentifikationsNummer()
    {
        return $this->glaeubigerIdentifikationsNummer;
    }

    /**
     * Sets the glaeubigerIdentifikationsNummer
     *
     * @param string $glaeubigerIdentifikationsNummer
     *
     * @return void
     */
    public function setGlaeubigerIdentifikationsNummer($glaeubigerIdentifikationsNummer)
    {
        $this->glaeubigerIdentifikationsNummer = (string)$glaeubigerIdentifikationsNummer;
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
     * Returns the info115
     *
     * @return Info115 $info115
     */
    public function getInfo115()
    {
        return $this->info115;
    }

    /**
     * Sets the info115
     *
     * @param Info115 $info115
     *
     * @return void
     */
    public function setInfo115(Info115 $info115 = null)
    {
        $this->info115 = $info115;
    }

    /**
     * Returns the publishStatus
     *
     * @return string $publishStatus
     */
    public function getPublishStatus()
    {
        return $this->publishStatus;
    }

    /**
     * Sets the publishStatus
     *
     * @param string $publishStatus
     *
     * @return void
     */
    public function setPublishStatus($publishStatus)
    {
        $this->publishStatus = (string)$publishStatus;
    }

    /**
     * Returns the publishDate
     *
     * @return \DateTime $publishDate
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Sets the publishDate
     *
     * @param \DateTime $publishDate
     *
     * @return void
     */
    public function setPublishDate(\DateTime $publishDate = null)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * Returns the publishedVersion
     *
     * @return string $publishedVersion
     */
    public function getPublishedVersion()
    {
        return $this->publishedVersion;
    }

    /**
     * Sets the publishedVersion
     *
     * @param string $publishedVersion
     *
     * @return void
     */
    public function setPublishedVersion($publishedVersion)
    {
        $this->publishedVersion = (string)$publishedVersion;
    }

    /**
     * Returns the version
     *
     * @return int $version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the version
     *
     * @param int $version
     *
     * @return void
     */
    public function setVersion($version)
    {
        $this->version = (int)$version;
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
     * Returns the createdBy
     *
     * @return string $createdBy
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets the createdBy
     *
     * @param string $createdBy
     *
     * @return void
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = (string)$createdBy;
    }

    /**
     * Returns the createdByMandant
     *
     * @return string $createdByMandant
     */
    public function getCreatedByMandant()
    {
        return $this->createdByMandant;
    }

    /**
     * Sets the createdByMandant
     *
     * @param string $createdByMandant
     *
     * @return void
     */
    public function setCreatedByMandant($createdByMandant)
    {
        $this->createdByMandant = (string)$createdByMandant;
    }

    /**
     * Returns the modifiedBy
     *
     * @return string $modifiedBy
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Sets the modifiedBy
     *
     * @param string $modifiedBy
     *
     * @return void
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = (string)$modifiedBy;
    }

    /**
     * Returns the modifiedByMandant
     *
     * @return string $modifiedByMandant
     */
    public function getModifiedByMandant()
    {
        return $this->modifiedByMandant;
    }

    /**
     * Sets the modifiedByMandant
     *
     * @param string $modifiedByMandant
     *
     * @return void
     */
    public function setModifiedByMandant($modifiedByMandant)
    {
        $this->modifiedByMandant = (string)$modifiedByMandant;
    }

    /**
     * Returns the releaseDate
     *
     * @return \DateTime $releaseDate
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Sets the releaseDate
     *
     * @param \DateTime $releaseDate
     *
     * @return void
     */
    public function setReleaseDate(\DateTime $releaseDate = null)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * Returns the lastPublishedReleaseDate
     *
     * @return \DateTime $lastPublishedReleaseDate
     */
    public function getLastPublishedReleaseDate()
    {
        return $this->lastPublishedReleaseDate;
    }

    /**
     * Sets the lastPublishedReleaseDate
     *
     * @param \DateTime $lastPublishedReleaseDate
     *
     * @return void
     */
    public function setLastPublishedReleaseDate(\DateTime $lastPublishedReleaseDate = null)
    {
        $this->lastPublishedReleaseDate = $lastPublishedReleaseDate;
    }

    /**
     * Returns the zugehoerigeBehoerde
     *
     * @return Behoerde $zugehoerigeBehoerde
     */
    public function getZugehoerigeBehoerde()
    {
        return $this->zugehoerigeBehoerde;
    }

    /**
     * Sets the zugehoerigeBehoerde
     *
     * @param Behoerde $zugehoerigeBehoerde
     *
     * @return void
     */
    public function setZugehoerigeBehoerde($zugehoerigeBehoerde = null)
    {
        if ($zugehoerigeBehoerde instanceof Behoerde) {
            $this->zugehoerigeBehoerde = $zugehoerigeBehoerde;
        } else {
            $this->zugehoerigeBehoerde = null;
        }
    }
}
