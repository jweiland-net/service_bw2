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
     * Filters a flat list of Organisationseinheiten by allowed parent IDs and
     * reconstructs the hierarchy from individual records.
     *
     * Returns only the root records (those in $allowedParentIds) with their
     * children assembled from the flat list up to $maxDepth levels. This
     * approach correctly handles stored data where a parent record does not
     * contain its full descendant tree.
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
        $descendants = $this->filterOrganisationseinheitenDescendantsByParentIds(
            $organisationseinheiten,
            $allowedParentIds,
            $maxDepth,
        );

        return $this->buildOrganisationseinheitenTree($descendants, $allowedParentIds);
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
     * Assembles a tree from a flat list of records by linking each record to its
     * parent via uebergeordneteOE. Returns only root records (those in $rootIds)
     * with their full subtree attached and children sorted by name.
     *
     * @param array<int, Record> $records
     * @param array<int|string> $rootIds
     * @return array<int, Record>
     */
    private function buildOrganisationseinheitenTree(array $records, array $rootIds): array
    {
        $byId = [];
        foreach ($records as $record) {
            $byId[$record->getId()] = $record;
        }

        $childrenByParentId = [];
        foreach ($records as $record) {
            $parent = $record->getUebergeordneteOE();
            if ($parent instanceof Record && isset($byId[$parent->getId()])) {
                $childrenByParentId[$parent->getId()][] = $record->getId();
            }
        }

        $roots = array_values(array_filter(
            $records,
            fn(Record $r): bool => in_array($r->getId(), $rootIds, true),
        ));

        return $this->sortOrganisationseinheitenByName(
            array_map(
                fn(Record $r): Record => $this->attachChildrenToRecord($r, $byId, $childrenByParentId),
                $roots,
            ),
        );
    }

    private function attachChildrenToRecord(Record $record, array $byId, array $childrenByParentId): Record
    {
        $childIds = $childrenByParentId[$record->getId()] ?? [];
        if ($childIds === []) {
            return $record->withData(array_merge($record->getData(), ['untergeordneteOEs' => []]));
        }

        $children = [];
        foreach ($childIds as $childId) {
            $children[] = $this->attachChildrenToRecord($byId[$childId], $byId, $childrenByParentId);
        }

        $sortedChildren = $this->sortOrganisationseinheitenByName($children);

        return $record->withData(array_merge(
            $record->getData(),
            ['untergeordneteOEs' => array_map(fn(Record $r): array => $r->getData(), $sortedChildren)],
        ));
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
