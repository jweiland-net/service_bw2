<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Traits;

trait FilterOrganisationseinheitenTrait
{
    /**
     * Filters a recursive organisationseinheiten tree.
     *
     * Only records with an ID contained in $allowedParentIds are allowed to appear
     * on the root level of the returned result. These records may exist anywhere
     * in the original recursive tree.
     *
     * Once such a record is found, its children are included recursively up to
     * $maxDepth levels below the matched root record.
     *
     * @param array<int, array<string, mixed>> $organisationseinheiten
     * @param array<int|string> $allowedParentIds
     * @return array<int, array<string, mixed>>
     */
    protected function filterOrganisationseinheitenByParentIds(
        array $organisationseinheiten,
        array $allowedParentIds,
        int $maxDepth = 2,
    ): array {
        $filteredOrganisationseinheiten = [];

        foreach ($organisationseinheiten as $organisationseinheit) {
            if (!is_array($organisationseinheit)) {
                continue;
            }

            $organisationseinheitId = $organisationseinheit['id'] ?? null;

            if (in_array($organisationseinheitId, $allowedParentIds, true)) {
                $filteredOrganisationseinheiten[] = $this->limitOrganisationseinheitenDepth(
                    $organisationseinheit,
                    0,
                    $maxDepth,
                );

                continue;
            }

            $untergeordneteOrganisationseinheiten = $this->getUntergeordneteOrganisationseinheiten(
                $organisationseinheit,
            );

            if ($untergeordneteOrganisationseinheiten !== []) {
                $filteredOrganisationseinheiten = [
                    ...$filteredOrganisationseinheiten,
                    ...$this->filterOrganisationseinheitenByParentIds(
                        $untergeordneteOrganisationseinheiten,
                        $allowedParentIds,
                        $maxDepth,
                    ),
                ];
            }
        }

        return $this->sortOrganisationseinheitenByName($filteredOrganisationseinheiten);
    }

    /**
     * Keeps a matched organisationseinheit and limits its recursive children
     * to the configured maximum depth.
     *
     * Depth starts at 0 for the matched root record.
     *
     * With $maxDepth = 2, the result contains:
     * - matched root record, depth 0
     * - direct children, depth 1
     * - grandchildren, depth 2
     *
     * Children below depth 2 are removed.
     *
     * @param array<string, mixed> $organisationseinheit
     * @return array<string, mixed>
     */
    protected function limitOrganisationseinheitenDepth(
        array $organisationseinheit,
        int $depth,
        int $maxDepth,
    ): array {
        $untergeordneteOrganisationseinheiten = $this->getUntergeordneteOrganisationseinheiten(
            $organisationseinheit,
        );

        if ($untergeordneteOrganisationseinheiten === [] || $depth >= $maxDepth) {
            $organisationseinheit['untergeordneteOrganisationseinheiten'] = [];

            return $organisationseinheit;
        }

        $limitedUntergeordneteOrganisationseinheiten = [];

        foreach ($untergeordneteOrganisationseinheiten as $untergeordneteOrganisationseinheit) {
            if (!is_array($untergeordneteOrganisationseinheit)) {
                continue;
            }

            $limitedUntergeordneteOrganisationseinheiten[] = $this->limitOrganisationseinheitenDepth(
                $untergeordneteOrganisationseinheit,
                $depth + 1,
                $maxDepth,
            );
        }

        $organisationseinheit['untergeordneteOrganisationseinheiten'] = $this->sortOrganisationseinheitenByName(
            $limitedUntergeordneteOrganisationseinheiten,
        );

        return $organisationseinheit;
    }

    /**
     * @param array<string, mixed> $organisationseinheit
     * @return array<int, array<string, mixed>>
     */
    protected function getUntergeordneteOrganisationseinheiten(array $organisationseinheit): array
    {
        $untergeordneteOrganisationseinheiten = $organisationseinheit['untergeordneteOrganisationseinheiten'] ?? [];

        if (!is_array($untergeordneteOrganisationseinheiten)) {
            return [];
        }

        return $untergeordneteOrganisationseinheiten;
    }

    /**
     * @param array<int, array<string, mixed>> $organisationseinheiten
     * @return array<int, array<string, mixed>>
     */
    protected function sortOrganisationseinheitenByName(array $organisationseinheiten): array
    {
        usort(
            $organisationseinheiten,
            static function (array $firstOrganisationseinheit, array $secondOrganisationseinheit): int {
                return strcasecmp(
                    (string)($firstOrganisationseinheit['name'] ?? ''),
                    (string)($secondOrganisationseinheit['name'] ?? ''),
                );
            },
        );

        return $organisationseinheiten;
    }
}
