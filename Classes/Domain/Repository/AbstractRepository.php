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
use Doctrine\DBAL\Exception;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Domain\Model\Record;
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

    public function findById(int $id): ?Record
    {
        $queryBuilder = $this->getQueryBuilder();

        try {
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
                ->fetchAssociative();
        } catch (Exception $e) {
            return null;
        }

        if (!is_array($record)) {
            return null;
        }

        return new Record(
            $record['id'],
            $record['name'],
            $record['type'],
            $record['language'],
            json_decode($record['data'], true),
        );
    }

    public function hasId(int $id): bool
    {
        $queryBuilder = $this->getQueryBuilder();

        try {
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
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * @return \Generator<Record>
     */
    public function findAll(string $language): \Generator
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryResult = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(static::CONTROLLER_TYPE),
                ),
            )
            ->executeQuery();

        try {
            while ($record = $queryResult->fetchAssociative()) {
                yield new Record(
                    $record['id'],
                    $record['name'],
                    $record['type'],
                    $record['language'],
                    json_decode($record['data'], true),
                );
            }
        } catch (Exception) {
        }
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
                    'type' => static::CONTROLLER_TYPE,
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
                $queryBuilder->expr()->eq(
                    'language',
                    $queryBuilder->createNamedParameter($language),
                ),
            )
            ->executeQuery()
            ->fetchFirstColumn();
    }

    public function deleteIds(array $ids, string $language): void
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder
            ->delete(self::TABLE)
            ->where(
                $queryBuilder->expr()->in(
                    'id',
                    $queryBuilder->createNamedParameter($ids, ArrayParameterType::INTEGER),
                ),
                $queryBuilder->expr()->eq(
                    'language',
                    $queryBuilder->createNamedParameter($language),
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
