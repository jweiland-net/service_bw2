<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Portal;

use JWeiland\ServiceBw2\Request\AbstractRequest;

/**
 * Request class for Search
 */
class Suche extends AbstractRequest
{
    public const TYP_NONE = '';

    public const TYP_LEISTUNG = 'LEISTUNG';

    public const TYP_LEBENSLAGE = 'LEBENSLAGE';

    public const TYP_ORGANISATIONSEINHEIT = 'ORGANISATIONSEINHEIT';

    public const SORT_NAME = 'name';

    public const SORT_RELEVANZ = 'relevanz';

    public function suche(string $q, string $typ = self::TYP_NONE, string $sortProperty = self::SORT_NAME): array
    {
        $parameters = ['q' => $q, 'sortProperty' => $sortProperty];
        if ($typ !== '' && $typ !== '0') {
            $parameters['typ'] = $typ;
        }

        return $this->client->request('/portal/suche', $parameters, true, true);
    }
}
