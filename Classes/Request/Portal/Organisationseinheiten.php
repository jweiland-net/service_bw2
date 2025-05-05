<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request\Portal;

use JWeiland\ServiceBw2\Request\AbstractRequest;
use JWeiland\ServiceBw2\Request\EntityRequestInterface;

/**
 * Request class for Organisationseinheiten
 */
class Organisationseinheiten extends AbstractRequest implements EntityRequestInterface
{
    public function findById(int $id): array
    {
        return $this->client->request('/portal/organisationseinheitsdetails/' . $id);
    }

    public function findAll(): array
    {
        return $this->client->request('/portal/organisationseinheiten', [], true, true);
    }

    public function findOrganisationseinheitenbaum(): array
    {
        return $this->client->request('/portal/organisationseinheitenbaum', [], true, true);
    }
}
