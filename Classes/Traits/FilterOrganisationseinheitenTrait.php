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
     * Filters a recursive organisationseinheiten tree.
     *
     * Only records with an ID contained in $allowedParentIds are allowed to appear
     * on the root level of the returned result. These records may exist anywhere
     * in the original recursive tree.
     *
     * Once such a record is found, its children are included recursively up to
     * $maxDepth levels below the matched root record.
     *
     * @param array<int, Record> $organisationseinheiten
     * @param array<int|string> $allowedParentIds
     * @return array<int, Record>
     */
    protected function filterOrganisationseinheitenByParentIds(
        array $organisationseinheiten,
        array $allowedParentIds,
        int $maxDepth = 2,
    ): array {
        $filteredOrganisationseinheiten = [];

        foreach ($organisationseinheiten as $organisationseinheit) {
            if (in_array($organisationseinheit->getId(), $allowedParentIds, true)) {
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
     */
    protected function limitOrganisationseinheitenDepth(
        Record $organisationseinheit,
        int $depth,
        int $maxDepth,
    ): Record {
        $untergeordneteOrganisationseinheiten = $this->getUntergeordneteOrganisationseinheiten(
            $organisationseinheit,
        );

        if ($untergeordneteOrganisationseinheiten === [] || $depth >= $maxDepth) {
            return $organisationseinheit->withData(
                array_merge($organisationseinheit->getData(), ['untergeordneteOrganisationseinheiten' => []]),
            );
        }

        $limitedChildren = array_map(
            fn(Record $child): Record => $this->limitOrganisationseinheitenDepth($child, $depth + 1, $maxDepth),
            $untergeordneteOrganisationseinheiten,
        );

        $sortedChildren = $this->sortOrganisationseinheitenByName($limitedChildren);

        return $organisationseinheit->withData(
            array_merge(
                $organisationseinheit->getData(),
                ['untergeordneteOrganisationseinheiten' => array_map(fn(Record $r): array => $r->getData(), $sortedChildren)],
            ),
        );
    }

    /**
     * @return array<int, Record>
     */
    protected function getUntergeordneteOrganisationseinheiten(Record $organisationseinheit): array
    {
        return $organisationseinheit->getUntergeordneteOrganisationseinheiten();
    }

    /**
     * @param array<int, Record> $organisationseinheiten
     * @return array<int, Record>
     */
    protected function sortOrganisationseinheitenByName(array $organisationseinheiten): array
    {
        usort(
            $organisationseinheiten,
            static fn(Record $first, Record $second): int => strcasecmp($first->getName(), $second->getName()),
        );

        return $organisationseinheiten;
    }
}
