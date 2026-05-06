<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'service-bw2.repository',
)]
readonly class OrganisationseinheitenRepository extends AbstractRepository implements RepositoryInterface
{
    public const CONTROLLER_TYPE = 'organisationseinheiten';
}
