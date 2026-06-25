<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Domain\Provider;

use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheitenbaum;
use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheitsdetails;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Domain\Provider\OrganisationseinheitenProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class OrganisationseinheitenProviderTest extends FunctionalTestCase
{
    protected OrganisationseinheitenProvider $subject;

    protected ServiceBwClient|MockObject $serviceBwClientMock;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceBwClientMock = $this->createMock(ServiceBwClient::class);

        $this->subject = new OrganisationseinheitenProvider(
            $this->serviceBwClientMock,
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $this->serviceBwClientMock,
            $this->subject,
        );
    }

    #[Test]
    public function findByIdWillRequestRecord()
    {
        $data = [
            'id' => 123,
            'name' => 'TYPO3',
        ];

        $this->serviceBwClientMock
            ->expects($this->atLeastOnce())
            ->method('requestRecord')
            ->with(
                self::isInstanceOf(Organisationseinheitsdetails::class),
                self::identicalTo('de'),
            )
            ->willReturn($data);

        self::assertSame(
            $data,
            $this->subject->findById(123, 'de'),
        );
    }

    #[Test]
    public function findAllWillRequestRecords()
    {
        $data = [
            [
                'id' => 123,
                'name' => 'TYPO3',
            ],
        ];

        $this->serviceBwClientMock
            ->expects($this->atLeastOnce())
            ->method('requestAll')
            ->with(
                self::isInstanceOf(Organisationseinheiten::class),
                self::identicalTo('de'),
            )
            ->willReturn((static function () use ($data): \Generator {
                yield from $data;
            })());

        self::assertSame(
            $data,
            iterator_to_array($this->subject->findAll('de')),
        );
    }

    #[Test]
    public function findOrganisationseinheitenTreesWillReturnRecordObjects()
    {
        $treeData = [
            ['id' => 10, 'name' => 'Root A', 'untergeordneteOrganisationseinheiten' => []],
            ['id' => 20, 'name' => 'Root B', 'untergeordneteOrganisationseinheiten' => []],
        ];

        $this->serviceBwClientMock
            ->expects($this->atLeastOnce())
            ->method('requestAll')
            ->with(
                self::isInstanceOf(Organisationseinheitenbaum::class),
                self::identicalTo('de'),
            )
            ->willReturn((static function () use ($treeData): \Generator {
                yield from $treeData;
            })());

        $result = $this->subject->findOrganisationseinheitenTrees('de');

        self::assertCount(2, $result);
        self::assertContainsOnlyInstancesOf(Record::class, $result);
        self::assertSame(10, $result[0]->getId());
        self::assertSame('Root A', $result[0]->getName());
        self::assertSame('organisationseinheiten', $result[0]->getType());
        self::assertSame('de', $result[0]->getLanguage());
        self::assertSame(20, $result[1]->getId());
    }

    #[Test]
    public function findOrganisationseinheitenTreesPreservesNestedChildrenInData()
    {
        $child = ['id' => 11, 'name' => 'Child', 'untergeordneteOrganisationseinheiten' => []];
        $treeData = [
            ['id' => 10, 'name' => 'Root', 'untergeordneteOrganisationseinheiten' => [$child]],
        ];

        $this->serviceBwClientMock
            ->expects($this->atLeastOnce())
            ->method('requestAll')
            ->willReturn((static function () use ($treeData): \Generator {
                yield from $treeData;
            })());

        $result = $this->subject->findOrganisationseinheitenTrees('de');
        $children = $result[0]->getUntergeordneteOrganisationseinheiten();

        self::assertCount(1, $children);
        self::assertInstanceOf(Record::class, $children[0]);
        self::assertSame(11, $children[0]->getId());
    }
}
