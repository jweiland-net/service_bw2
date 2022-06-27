<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Helper;

use JWeiland\ServiceBw2\Request\Portal\Leistungen;

/**
 * Prozesse are a new alternative to PDF forms in Service BW.
 * Because the API does not feature all required functionality right now,
 * this class is a temporary helper class to work with Prozesse.
 *
 * @deprecated in version 5.0, will be removed in 6.0.
 * @internal methods will be removed if the API adds some similar functionality
 */
class ProzesseHelper
{
    /**
     * @var Leistungen
     */
    protected $leistungen;

    public function __construct(Leistungen $leistungen)
    {
        $this->leistungen = $leistungen;
    }

    public function findAll(): array
    {
        trigger_error(
            'ProzesseHelper::findAll() is deprecated! Use Leistungen::findAll() with LeistungenHelper::getAdditionalData() instead!',
            E_USER_DEPRECATED
        );
        $prozesse = [];
        foreach ($this->leistungen->findAll() as $leistungFromList) {
            $leistung = $this->leistungen->findById($leistungFromList['id']);
            if ($leistung['prozesse']) {
                $prozesse = array_merge($prozesse, $leistung['prozesse']);
            }
        }
        return $prozesse;
    }
}
