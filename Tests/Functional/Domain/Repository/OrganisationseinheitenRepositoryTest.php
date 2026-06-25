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
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class OrganisationseinheitenRepositoryTest extends FunctionalTestCase
{
    protected OrganisationseinheitenRepository $subject;

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

        $this->subject = new OrganisationseinheitenRepository(
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
                6021657,
                'Abfallwirtschaft',
                'organisationseinheiten',
                'de',
                ['id' => 6021657, 'uebergeordneteOE' => ['id' => 6000000, 'name' => 'Root']],
            ),
            $this->subject->findById(6021657),
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
            $this->subject->hasId(6021657),
        );
    }

    #[Test]
    public function findAllWillReturnTwoRecords(): void
    {
        $records = iterator_to_array($this->subject->findAll('de'));

        self::assertCount(
            7,
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
                6021657,
                'Abfallwirtschaft',
                'organisationseinheiten',
                'de',
                ['id' => 6021657, 'uebergeordneteOE' => ['id' => 6000000, 'name' => 'Root']],
            ),
            $records[0],
        );

        self::assertEquals(
            new Record(
                6021675,
                'Allgemeine Verwaltung',
                'organisationseinheiten',
                'de',
                ['id' => 6021675, 'uebergeordneteOE' => ['id' => 6000000, 'name' => 'Root']],
            ),
            $records[1],
        );
    }

    #[Test]
    public function addOrUpdateWillAddRecord(): void
    {
        $record = [
            'id' => 6023183,
            'name' => 'Voranmeldung der Eheschließung',
            'controllerType' => 'organisationseinheiten',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertTrue(
            $this->subject->hasId(6023183),
        );
    }

    #[Test]
    public function addOrUpdateWillUpdateRecord(): void
    {
        $record = [
            'id' => 6021657,
            'name' => 'Müllwirtschaft',
            'controllerType' => 'organisationseinheiten',
            'language' => 'de',
            'data' => [],
        ];

        $this->subject->addOrUpdate($record, 'de');

        self::assertSame(
            $record['name'],
            $this->subject->findById(6021657)->getName(),
        );
    }

    #[Test]
    public function getAllIdsWillReturnAllIds(): void
    {
        self::assertSame(
            [
                6021657,
                6021675,
                100,
                101,
                102,
                103,
                104,
            ],
            $this->subject->getAllIds('de'),
        );
    }

    #[Test]
    public function deleteIdsWillDeleteOneRecord(): void
    {
        $this->subject->deleteIds([6021657], 'de');

        self::assertFalse(
            $this->subject->hasId(6021657),
        );

        self::assertTrue(
            $this->subject->hasId(6021675),
        );
    }

    // -------------------------------------------------------------------------
    // getOrganisationseinheitenTree
    // -------------------------------------------------------------------------

    #[Test]
    public function getOrganisationseinheitenTreeReturnsEmptyArrayForEmptyIds(): void
    {
        self::assertSame([], $this->subject->getOrganisationseinheitenTree([], 'de'));
    }

    #[Test]
    public function getOrganisationseinheitenTreeReturnsEmptyArrayForUnknownIds(): void
    {
        self::assertSame([], $this->subject->getOrganisationseinheitenTree([99999], 'de'));
    }

    #[Test]
    public function getOrganisationseinheitenTreeReturnsEmptyArrayForWrongLanguage(): void
    {
        self::assertSame([], $this->subject->getOrganisationseinheitenTree([100], 'pt'));
    }

    #[Test]
    public function getOrganisationseinheitenTreeBuildsThreeLevelsAtDefaultMaxDepth(): void
    {
        $result = $this->subject->getOrganisationseinheitenTree([100], 'de');

        self::assertCount(1, $result);
        self::assertSame(100, $result[0]->getId());

        $children = $result[0]->getUntergeordneteOEs();
        self::assertCount(2, $children);
        self::assertSame(104, $children[0]->getId());
        self::assertSame(101, $children[1]->getId());

        $grandchildren = $children[1]->getUntergeordneteOEs();
        self::assertCount(1, $grandchildren);
        self::assertSame(102, $grandchildren[0]->getId());

        self::assertSame([], $grandchildren[0]->getUntergeordneteOEs());
    }

    #[Test]
    public function getOrganisationseinheitenTreeRespectsMaxDepthOne(): void
    {
        $result = $this->subject->getOrganisationseinheitenTree([100], 'de', 1);

        self::assertCount(1, $result);

        $children = $result[0]->getUntergeordneteOEs();
        self::assertCount(2, $children);

        foreach ($children as $child) {
            self::assertSame([], $child->getUntergeordneteOEs());
        }
    }

    #[Test]
    public function getOrganisationseinheitenTreeRespectsMaxDepthZero(): void
    {
        $result = $this->subject->getOrganisationseinheitenTree([100], 'de', 0);

        self::assertCount(1, $result);
        self::assertSame([], $result[0]->getUntergeordneteOEs());
    }

    #[Test]
    public function getOrganisationseinheitenTreeSortsChildrenAlphabetically(): void
    {
        $result = $this->subject->getOrganisationseinheitenTree([100], 'de', 1);

        $children = $result[0]->getUntergeordneteOEs();

        self::assertSame('Alpha', $children[0]->getName());
        self::assertSame('Omega', $children[1]->getName());
    }
}
