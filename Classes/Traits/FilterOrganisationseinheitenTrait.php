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
     * Filters a flat list of Organisationseinheiten to only those whose own ID is in $allowedParentIds.
     * Intended for frontend tree rendering: each matched record already carries its children
     * in untergeordneteOEs, so only the root level is returned here. The nested tree is
     * depth-limited to $maxDepth and sorted by name.
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
        $filtered = array_values(array_filter(
            $organisationseinheiten,
            fn(Record $oe): bool => $this->hasAllowedParentInChain($oe, $allowedParentIds),
        ));

        return $this->sortOrganisationseinheitenByName(
            array_map(
                fn(Record $oe): Record => $this->limitOrganisationseinheitenDepth($oe, 0, $maxDepth),
                $filtered,
            ),
        );
    }

    private function hasAllowedParentInChain(Record $oe, array $allowedParentIds): bool
    {
        return in_array($oe->getId(), $allowedParentIds, true);
    }

    /**
     * Returns a flat list of all Organisationseinheiten whose ancestor chain (up to $maxDepth
     * levels) contains one of the $allowedParentIds. Intended for use cases such as Solr indexing
     * where every individual record must appear as a separate item rather than as a nested tree.
     *
     * @param array<int, Record> $organisationseinheiten
     * @param array<int|string> $allowedParentIds
     * @return array<int, Record>
     */
    protected function filterOrganisationseinheitenDescendantsByParentIds(
        array $organisationseinheiten,
        array $allowedParentIds,
        int $maxDepth = 2,
    ): array {
        return array_values(array_filter(
            $organisationseinheiten,
            fn(Record $oe): bool => $this->hasAllowedAncestorWithinDepth($oe, $allowedParentIds, $maxDepth),
        ));
    }

    private function hasAllowedAncestorWithinDepth(Record $oe, array $allowedParentIds, int $remainingDepth): bool
    {
        if (in_array($oe->getId(), $allowedParentIds, true)) {
            return true;
        }

        if ($remainingDepth === 0) {
            return false;
        }

        $parent = $oe->getUebergeordneteOE();
        if (!$parent instanceof Record) {
            return false;
        }

        return $this->hasAllowedAncestorWithinDepth($parent, $allowedParentIds, $remainingDepth - 1);
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
        $untergeordneteOEs = $organisationseinheit->getUntergeordneteOEs();

        if ($untergeordneteOEs === [] || $depth >= $maxDepth) {
            return $organisationseinheit->withData(
                array_merge($organisationseinheit->getData(), ['untergeordneteOEs' => []]),
            );
        }

        $limitedChildren = array_map(
            fn(Record $child): Record => $this->limitOrganisationseinheitenDepth($child, $depth + 1, $maxDepth),
            $untergeordneteOEs,
        );

        $sortedChildren = $this->sortOrganisationseinheitenByName($limitedChildren);

        return $organisationseinheit->withData(
            array_merge(
                $organisationseinheit->getData(),
                ['untergeordneteOEs' => array_map(fn(Record $r): array => $r->getData(), $sortedChildren)],
            ),
        );
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
