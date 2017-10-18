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
class GeoKodierung extends AbstractEntity
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
    protected $srsName = '';

    /**
     * @var int
     */
    protected $x = 0;

    /**
     * @var int
     */
    protected $y = 0;

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
     * Returns the srsName
     *
     * @return string $srsName
     */
    public function getSrsName()
    {
        return $this->srsName;
    }

    /**
     * Sets the srsName
     *
     * @param string $srsName
     *
     * @return void
     */
    public function setSrsName($srsName)
    {
        $this->srsName = (string)$srsName;
    }

    /**
     * Returns the x
     *
     * @return int $x
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Sets the x
     *
     * @param int $x
     *
     * @return void
     */
    public function setX($x)
    {
        $this->x = (int)$x;
    }

    /**
     * Returns the y
     *
     * @return int $y
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Sets the y
     *
     * @param int $y
     *
     * @return void
     */
    public function setY($y)
    {
        $this->y = (int)$y;
    }
}
