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
class BankVerbindung extends AbstractEntity
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
    protected $beschreibung = '';

    /**
     * @var string
     */
    protected $empfaenger = '';

    /**
     * @var string
     */
    protected $bankInstitut = '';

    /**
     * @var string
     */
    protected $bankVerbindungNational = '';

    /**
     * Array with iban and bic
     *
     * @var string
     */
    protected $bankVerbindungInternational = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWeiland\ServiceBw2\Domain\Model\Gueltigkeit>
     */
    protected $gueltigkeiten;

    /**
     * BankVerbindung constructor.
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
     * Returns the empfaenger
     *
     * @return string $empfaenger
     */
    public function getEmpfaenger()
    {
        return $this->empfaenger;
    }

    /**
     * Sets the empfaenger
     *
     * @param string $empfaenger
     *
     * @return void
     */
    public function setEmpfaenger($empfaenger)
    {
        $this->empfaenger = (string)$empfaenger;
    }

    /**
     * Returns the bankInstitut
     *
     * @return string $bankInstitut
     */
    public function getBankInstitut()
    {
        return $this->bankInstitut;
    }

    /**
     * Sets the bankInstitut
     *
     * @param string $bankInstitut
     *
     * @return void
     */
    public function setBankInstitut($bankInstitut)
    {
        $this->bankInstitut = (string)$bankInstitut;
    }

    /**
     * Returns the bankVerbindungNational
     *
     * @return string $bankVerbindungNational
     */
    public function getBankVerbindungNational()
    {
        return $this->bankVerbindungNational;
    }

    /**
     * Sets the bankVerbindungNational
     *
     * @param string $bankVerbindungNational
     *
     * @return void
     */
    public function setBankVerbindungNational($bankVerbindungNational)
    {
        $this->bankVerbindungNational = (string)$bankVerbindungNational;
    }

    /**
     * Returns the bankVerbindungInternational
     *
     * @return string $bankVerbindungInternational
     */
    public function getBankVerbindungInternational()
    {
        return $this->bankVerbindungInternational;
    }

    /**
     * Sets the bankVerbindungInternational
     *
     * @param string $bankVerbindungInternational
     *
     * @return void
     */
    public function setBankVerbindungInternational($bankVerbindungInternational)
    {
        $this->bankVerbindungInternational = (string)$bankVerbindungInternational;
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
