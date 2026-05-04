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
     * @param \Generator<int, Record> $organisationseinheiten
     */
    protected function filterOrganisationseinheitenByParentIds(
        \Generator $organisationseinheiten,
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
            static fn(Record $a, Record $b): int => strcasecmp($a->getName(), $b->getName()),
        );

        /** @var Record $organisationseinheit */
        foreach ($organisationseinheiten as $organisationseinheit) {
            $untergeordneteOrganisationseinheiten = $organisationseinheit->getData()['untergeordneteOrganisationseinheiten'] ?? [];

            if (in_array($organisationseinheit->getId(), $allowedParentIds, true)) {
                $filteredOrganisationseinheiten[] = $organisationseinheit;
            } elseif ($untergeordneteOrganisationseinheiten !== [] && $depth < $maxDepth) {
                $untergeordneteOrganisationseinheitenGenerator = $this->createOrganisationseinheitenGenerator(
                    $untergeordneteOrganisationseinheiten,
                    $language,
                );

                $filteredUntergeordneteOrganisationseinheiten = $this->filterOrganisationseinheitenByParentIds(
                    $untergeordneteOrganisationseinheitenGenerator,
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

    /**
     * @return \Generator<Record>
     */
    private function createOrganisationseinheitenGenerator(iterable $items, string $language): \Generator
    {
        foreach ($items as $item) {
            yield new Record(
                $item['id'],
                $item['name'],
                '',
                $language,
                $item,
            );
        }
    }
}
