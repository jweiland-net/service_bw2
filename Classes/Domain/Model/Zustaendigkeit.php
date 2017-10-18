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
class Zustaendigkeit extends AbstractEntity
{
    /**
     * This is NOT the ID of TYPO3 DB. It's the original ID from Service BW
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @var int
     */
    protected $zustaendigkeitId = 0;

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
     * Returns the zustaendigkeitId
     *
     * @return int $zustaendigkeitId
     */
    public function getZustaendigkeitId()
    {
        return $this->zustaendigkeitId;
    }

    /**
     * Sets the zustaendigkeitId
     *
     * @param int $zustaendigkeitId
     *
     * @return void
     */
    public function setZustaendigkeitId($zustaendigkeitId)
    {
        $this->zustaendigkeitId = (int)$zustaendigkeitId;
    }
}
