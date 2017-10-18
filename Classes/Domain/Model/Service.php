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
class Service extends AbstractEntity
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
    protected $display_name = '';

    /**
     * @var string
     */
    protected $stufe = '';

    /**
     * @var string
     */
    protected $publishStatus = '';

    /**
     * @var int
     */
    protected $publishDate = 0;

    /**
     * @var string
     */
    protected $publishedVersion = '';

    /**
     * @var int
     */
    protected $version = 0;

    /**
     * @var int
     */
    protected $modifyDate = 0;

    /**
     * @var int
     */
    protected $createDate = 0;

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
     * @var int
     */
    protected $releaseDate = 0;

    /**
     * @var int
     */
    protected $lastPublishedReleaseDate = 0;

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
     * Returns the display_name
     *
     * @return string $display_name
     */
    public function getDisplay_name()
    {
        return $this->display_name;
    }

    /**
     * Sets the display_name
     *
     * @param string $display_name
     *
     * @return void
     */
    public function setDisplay_name($display_name)
    {
        $this->display_name = (string)$display_name;
    }

    /**
     * Returns the stufe
     *
     * @return string $stufe
     */
    public function getStufe()
    {
        return $this->stufe;
    }

    /**
     * Sets the stufe
     *
     * @param string $stufe
     *
     * @return void
     */
    public function setStufe($stufe)
    {
        $this->stufe = (string)$stufe;
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
     * @return int $publishDate
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Sets the publishDate
     *
     * @param int $publishDate
     *
     * @return void
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = (int)$publishDate;
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
     * @return int $releaseDate
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Sets the releaseDate
     *
     * @param int $releaseDate
     *
     * @return void
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = (int)$releaseDate;
    }

    /**
     * Returns the lastPublishedReleaseDate
     *
     * @return int $lastPublishedReleaseDate
     */
    public function getLastPublishedReleaseDate()
    {
        return $this->lastPublishedReleaseDate;
    }

    /**
     * Sets the lastPublishedReleaseDate
     *
     * @param int $lastPublishedReleaseDate
     *
     * @return void
     */
    public function setLastPublishedReleaseDate($lastPublishedReleaseDate)
    {
        $this->lastPublishedReleaseDate = (int)$lastPublishedReleaseDate;
    }
}
