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
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

abstract readonly class AbstractRepository implements RepositoryInterface
{
    public const TABLE = 'tx_servicebw2_response';

    public function __construct(
        protected ServiceBwClient $client,
        protected ConnectionPool $connectionPool,
    ) {}

    public function findById(int $id): ?array
    {
        $queryBuilder = $this->getQueryBuilder();

        $record = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->createNamedParameter($id, Connection::PARAM_INT),
                ),
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE),
                ),
            )
            ->executeQuery()
            ->fetchOne();

        return is_array($record) ? $record : null;
    }

    public function hasId(int $id): bool
    {
        $queryBuilder = $this->getQueryBuilder();

        return (bool)$queryBuilder
            ->count('id')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->createNamedParameter($id, Connection::PARAM_INT),
                ),
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE),
                ),
            )
            ->executeQuery()
            ->fetchOne();
    }

    public function findAll(string $language): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE),
                ),
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function addOrUpdate(array $record, string $language): void
    {
        $id = (int)($record['id'] ?? 0);

        if ($id === 0) {
            return;
        }

        if ($this->hasId($id)) {
            $connection = $this->connectionPool->getConnectionForTable(self::TABLE);
            $connection->update(
                self::TABLE,
                [
                    'name' => $record['name'],
                    'data' => $record,
                ],
                [
                    'id' => $id,
                ],
            );
        } else {
            $connection = $this->connectionPool->getConnectionForTable(self::TABLE);
            $connection->insert(
                self::TABLE,
                [
                    'id' => $id,
                    'crdate' => time(),
                    'name' => $record['name'],
                    'type' => static::CONTROLLER_TYPE,
                    'language' => $language,
                    'data' => $record,
                ],
            );
        }
    }

    public function getAllIds(string $language): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->select('id')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE),
                ),
            )
            ->executeQuery()
            ->fetchFirstColumn();
    }

    public function deleteIds(array $ids): void
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder
            ->delete(self::TABLE)
            ->where(
                $queryBuilder->expr()->in(
                    'id',
                    $queryBuilder->createNamedParameter($ids, ArrayParameterType::INTEGER),
                ),
            )
            ->executeStatement();
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        // Remove everything. We do not have any enableFields not deleted activated in TCA
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);
        $queryBuilder
            ->getRestrictions()
            ->removeAll();

        return $queryBuilder;
    }
}
