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
use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheitsdetails;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
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
}
