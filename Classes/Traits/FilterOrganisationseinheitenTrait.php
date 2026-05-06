<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Traits;

use JWeiland\ServiceBw2\Domain\Model\Record;

trait FilterOrganisationseinheitenTrait
{
    /**
     * This method filters the organisationseinheiten tree bypassed parent ids. All matching parents
     * will be added to the result arrays root, including all of their children.
     *
     * @param Record[] $organisationseinheiten
     */
    protected function filterOrganisationseinheitenByParentIds(
        array $organisationseinheiten,
        array $allowedParentIds,
        string $language,
        int $maxDepth = 2,
        int $depth = 0,
    ): array {
        $filteredOrganisationseinheiten = [];
        $allowedParentIds = array_map('intval', $allowedParentIds);
        $organisationseinheiten = iterator_to_array($organisationseinheiten);

        // The Service BW API currently does not sort organisationseinheiten reliably.
        // This fallback sorting can be removed once the API issue has been fixed.
        usort(
            $organisationseinheiten,
            static fn(array $a, array $b): int => strcasecmp($a['name'], $b['name']),
        );

        foreach ($organisationseinheiten as $organisationseinheit) {
            $untergeordneteOrganisationseinheiten = $organisationseinheit['untergeordneteOrganisationseinheiten'] ?? [];

            if (in_array($organisationseinheit['id'], $allowedParentIds, true)) {
                $filteredOrganisationseinheiten[] = $organisationseinheit;
            } elseif ($untergeordneteOrganisationseinheiten !== [] && $depth < $maxDepth) {
                $filteredUntergeordneteOrganisationseinheiten = $this->filterOrganisationseinheitenByParentIds(
                    $untergeordneteOrganisationseinheiten,
                    $allowedParentIds,
                    $language,
                    $maxDepth,
                    $depth + 1,
                );

                array_push(
                    $filteredOrganisationseinheiten,
                    ...$filteredUntergeordneteOrganisationseinheiten,
                );
            }
        }

        return $filteredOrganisationseinheiten;
    }
}
