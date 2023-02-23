<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Utility;

use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;

/**
 * Class ServiceBwUtility
 * Utility for general static methods
 */
class ServiceBwUtility
{
    /**
     * This method filters the organisationseinheiten tree by passed parent ids. All matching parents
     * will be added to the result arrays root including all of their children.
     */
    public static function filterOrganisationseinheitenByParentIds(
        array $organisationseinheiten,
        array $allowedParentIds,
        int $maxDepth = 2,
        int $depth = 0
    ): array {
        $filteredOrganisationseinheiten = [];
        foreach ($organisationseinheiten as $organisationseinheit) {
            if (in_array((string)$organisationseinheit['id'], $allowedParentIds, true)) {
                $filteredOrganisationseinheiten[] = $organisationseinheit;
            } elseif ($organisationseinheit['untergeordneteOrganisationseinheiten'] && $depth < $maxDepth) {
                array_push(
                    $filteredOrganisationseinheiten,
                    ...static::filterOrganisationseinheitenByParentIds(
                        $organisationseinheit['untergeordneteOrganisationseinheiten'],
                        $allowedParentIds,
                        $depth++
                    )
                );
            }
        }

        return $filteredOrganisationseinheiten;
    }

    /**
     * @return string full qualified name of replacement or original value of $repositoryClass if there is no replacement
     * @internal to be used for compatibility reason. Remove in version 6.0!
     */
    public static function getRepositoryReplacement(string $repositoryClass): string
    {
        $mapping = [
            'JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository' => Organisationseinheiten::class,
            'JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository' => Leistungen::class,
            'JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository' => Lebenslagen::class,
        ];

        if (array_key_exists($repositoryClass, $mapping)) {
            $repositoryClass = $mapping[$repositoryClass];
        }

        return $repositoryClass;
    }
}
