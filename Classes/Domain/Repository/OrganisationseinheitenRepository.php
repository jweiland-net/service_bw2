<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use Doctrine\DBAL\ArrayParameterType;
use JWeiland\ServiceBw2\Domain\Model\Record;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'service-bw2.repository',
)]
readonly class OrganisationseinheitenRepository extends AbstractRepository implements RepositoryInterface
{
    public const CONTROLLER_TYPE = 'organisationseinheiten';

    /**
     * Fetches a tree of Organisationseinheiten from the database starting from the given root IDs.
     * For each level, child IDs are extracted from the stored untergeordneteOEs data and fetched
     * via a single IN query. Recursion stops when $maxDepth reaches zero.
     *
     * @param array<int|string> $rootIds
     * @return array<int, Record>
     */
    public function getOrganisationseinheitenTree(
        array $rootIds,
        string $language,
        int $maxDepth = 2,
    ): array {
        if ($rootIds === [] || $maxDepth < 0) {
            return [];
        }

        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->in(
                    'id',
                    $queryBuilder->createNamedParameter(
                        array_values(array_map(intval(...), $rootIds)),
                        ArrayParameterType::INTEGER,
                    ),
                ),
                $queryBuilder->expr()->eq('type', $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE)),
                $queryBuilder->expr()->eq('language', $queryBuilder->createNamedParameter($language)),
            )
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        $records = [];
        $allChildIds = [];

        foreach ($rows as $row) {
            $data = json_decode((string)($row['data'] ?? '{}'), true) ?? [];
            $records[] = new Record((int)$row['id'], (string)$row['name'], static::CONTROLLER_TYPE, $language, $data);
            foreach ($data['untergeordneteOEs'] ?? [] as $child) {
                if (isset($child['id'])) {
                    $allChildIds[] = (int)$child['id'];
                }
            }
        }

        $childrenByParentId = [];
        if ($allChildIds !== [] && $maxDepth > 0) {
            foreach ($this->getOrganisationseinheitenTree($allChildIds, $language, $maxDepth - 1) as $child) {
                $parentId = (int)(($child->getData()['uebergeordneteOE']['id'] ?? 0));
                $childrenByParentId[$parentId][] = $child;
            }
        }

        $result = [];
        foreach ($records as $record) {
            $myChildren = $childrenByParentId[$record->getId()] ?? [];
            usort($myChildren, fn(Record $a, Record $b): int => strcasecmp($a->getName(), $b->getName()));
            $result[] = $record->withData(array_merge(
                $record->getData(),
                ['untergeordneteOEs' => array_map(fn(Record $r): array => $r->getData(), $myChildren)],
            ));
        }

        usort($result, fn(Record $a, Record $b): int => strcasecmp($a->getName(), $b->getName()));

        return $result;
    }
}
