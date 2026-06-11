<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Domain\Repository;

use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class LeistungenRepositoryTest extends FunctionalTestCase
{
    protected LeistungenRepository $subject;

    protected ServiceBwClient&MockObject $serviceBwClientMock;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_servicebw2_response.csv');

        $this->serviceBwClientMock = $this->createMock(ServiceBwClient::class);

        $this->subject = new LeistungenRepository(
            $this->serviceBwClientMock,
            $this->getConnectionPool(),
        );
    }

    #[Test]
    public function findByIdWillReturnNull(): void
    {
        self::assertNull(
            $this->subject->findById(1234),
        );
    }

    #[Test]
    public function findByIdWillReturnRecord(): void
    {
        self::assertEquals(
            new Record(
                4640,
                'Abfall- und Kreislaufwirtschaftsrecht',
                'leistungen',
                'de',
                [],
            ),
            $this->subject->findById(4640),
        );
    }

    #[Test]
    public function hasIdWillReturnFalse(): void
    {
        self::assertFalse(
            $this->subject->hasId(1234),
        );
    }

    #[Test]
    public function hasIdWillReturnTrue(): void
    {
        self::assertTrue(
            $this->subject->hasId(4640),
        );
    }

    #[Test]
    public function findAllWillReturnTwoRecords(): void
    {
        $records = iterator_to_array($this->subject->findAll('de'));

        self::assertCount(
            2,
            $records,
        );
    }

    #[Test]
    public function findAllWillReturnNoRecords(): void
    {
        $records = iterator_to_array($this->subject->findAll('pt'));

        self::assertCount(
            0,
            $records,
        );
    }

    #[Test]
    public function findAllWillReturnRecords(): void
    {
        $records = [];
        foreach ($this->subject->findAll('de') as $record) {
            $records[] = $record;
        }

        self::assertEquals(
            new Record(
                4640,
                'Abfall- und Kreislaufwirtschaftsrecht',
                'leistungen',
                'de',
                [],
            ),
            $records[0],
        );

        self::assertEquals(
            new Record(
                166,
                'Abfall und Müll entsorgen',
                'leistungen',
                'de',
                [],
            ),
            $records[1],
        );
    }

    #[Test]
    public function addOrUpdateWillAddRecord(): void
    {
        $record = [
            'id' => 4632,
            'name' => 'Arbeitsschutz',
            'controllerType' => 'leistungen',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertTrue(
            $this->subject->hasId(4632),
        );
    }

    #[Test]
    public function addOrUpdateWillUpdateRecord(): void
    {
        $record = [
            'id' => 166,
            'name' => 'Müllentsorgung',
            'controllerType' => 'leistungen',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertSame(
            $record['name'],
            $this->subject->findById(166)->getName(),
        );
    }

    #[Test]
    public function getAllIdsWillReturnAllIds(): void
    {
        self::assertSame(
            [
                4640,
                166,
            ],
            $this->subject->getAllIds('de'),
        );
    }

    #[Test]
    public function deleteIdsWillDeleteOneRecord(): void
    {
        $this->subject->deleteIds([4640], 'de');

        self::assertFalse(
            $this->subject->hasId(4640),
        );

        self::assertTrue(
            $this->subject->hasId(166),
        );
    }
}
