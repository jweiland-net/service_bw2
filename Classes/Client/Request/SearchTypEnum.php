<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Client\Request;

enum SearchTypEnum: string
{
    case LEISTUNG = 'LEISTUNG';
    case LEBENSLAGE = 'LEBENSLAGE';
    case ORGANISATIONSEINHEIT = 'ORGANISATIONSEINHEIT';
}
