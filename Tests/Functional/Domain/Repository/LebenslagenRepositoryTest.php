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
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class LebenslagenRepositoryTest extends FunctionalTestCase
{
    protected LebenslagenRepository $subject;

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

        $this->subject = new LebenslagenRepository(
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
                5000646,
                'Abbruch einer baulichen Anlage',
                'lebenslagen',
                'de',
                [],
            ),
            $this->subject->findById(5000646),
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
            $this->subject->hasId(5000646),
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
                5000646,
                'Abbruch einer baulichen Anlage',
                'lebenslagen',
                'de',
                [],
            ),
            $records[0],
        );

        self::assertEquals(
            new Record(
                5001070,
                'Abfallentsorgung und Müllgebühr',
                'lebenslagen',
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
            'id' => 5001671,
            'name' => 'Anregung einer Betreuung',
            'controllerType' => 'lebenslagen',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertTrue(
            $this->subject->hasId(5001671),
        );
    }

    #[Test]
    public function addOrUpdateWillUpdateRecord(): void
    {
        $record = [
            'id' => 5001070,
            'name' => 'Abfallentsorgung',
            'controllerType' => 'lebenslagen',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertSame(
            $record['name'],
            $this->subject->findById(5001070)->getName(),
        );
    }

    #[Test]
    public function getAllIdsWillReturnAllIds(): void
    {
        self::assertSame(
            [
                5000646,
                5001070,
            ],
            $this->subject->getAllIds('de'),
        );
    }

    #[Test]
    public function deleteIdsWillDeleteOneRecord(): void
    {
        $this->subject->deleteIds([5000646], 'de');

        self::assertFalse(
            $this->subject->hasId(5000646),
        );

        self::assertTrue(
            $this->subject->hasId(5001070),
        );
    }
}
