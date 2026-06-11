<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Command;

use JWeiland\ServiceBw2\Command\CacheWarmupCommand;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Domain\Provider\LebenslagenProvider;
use JWeiland\ServiceBw2\Domain\Provider\LeistungenProvider;
use JWeiland\ServiceBw2\Domain\Provider\OrganisationseinheitenProvider;
use JWeiland\ServiceBw2\Domain\Provider\ProviderFactory;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class CacheWarmupCommandTest extends FunctionalTestCase
{
    protected CacheWarmupCommand $subject;

    protected LebenslagenProvider&MockObject $lebenslagenProviderMock;

    protected LeistungenProvider&MockObject $leistungenProviderMock;

    protected OrganisationseinheitenProvider&MockObject $organisationseinheitenProviderMock;

    protected RepositoryFactory $repositoryFactory;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_servicebw2_response.csv');

        $this->lebenslagenProviderMock = $this->createMock(LebenslagenProvider::class);
        $this->leistungenProviderMock = $this->createMock(LeistungenProvider::class);
        $this->organisationseinheitenProviderMock = $this->createMock(OrganisationseinheitenProvider::class);

        $providerFactory = new ProviderFactory([
            $this->lebenslagenProviderMock,
            $this->leistungenProviderMock,
            $this->organisationseinheitenProviderMock,
        ]);

        $this->repositoryFactory = $this->get(RepositoryFactory::class);

        $this->subject = new CacheWarmupCommand(
            new ExtConf(allowedLanguages: 'de=de'),
            $providerFactory,
            $this->repositoryFactory,
            $this->createMock(LoggerInterface::class),
        );
    }

    #[Test]
    public function executeWithEmptyLebenslagenWillRemoveAllLebenslagen(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->atLeastOnce())
            ->method('getOption')
            ->willReturnMap([
                ['locales', 'de,pt'],
                ['include-lebenslagen', 'include-lebenslagen'],
            ]);

        $output = $this->createMock(OutputInterface::class);

        $this->subject->run($input, $output);

        $repository = $this->repositoryFactory->getRepository(ControllerTypeEnum::LEBENSLAGEN);
        $records = iterator_to_array($repository->findAll('de'));

        self::assertCount(
            0,
            $records,
        );
    }

    #[Test]
    public function executeWithLebenslagenWillAddLebenslagen(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->atLeastOnce())
            ->method('getOption')
            ->willReturnMap([
                ['locales', 'de,pt'],
                ['include-lebenslagen', 'include-lebenslagen'],
            ]);

        $output = $this->createMock(OutputInterface::class);

        $this->lebenslagenProviderMock
            ->expects($this->atLeastOnce())
            ->method('findAll')
            ->with('de')
            ->willReturn((static function (): \Generator {
                yield 123 => ['name' => 'foo'];
            })());
        $this->lebenslagenProviderMock
            ->expects($this->atLeastOnce())
            ->method('findById')
            ->with(123)
            ->willReturn([
                'id' => 123,
                'name' => 'foo',
                'type' => 'lebenslagen',
                'language' => 'de',
                'data' => '{}',
            ]);

        $this->subject->run($input, $output);

        $repository = $this->repositoryFactory->getRepository(ControllerTypeEnum::LEBENSLAGEN);
        $records = iterator_to_array($repository->findAll('de'));

        self::assertCount(
            1,
            $records,
        );

        self::assertTrue(
            $repository->hasId(123),
        );
    }

    #[Test]
    public function executeWithLebenslagenWillUpdateLebenslagen(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->atLeastOnce())
            ->method('getOption')
            ->willReturnMap([
                ['locales', 'de,pt'],
                ['include-lebenslagen', 'include-lebenslagen'],
            ]);

        $output = $this->createMock(OutputInterface::class);

        $this->lebenslagenProviderMock
            ->expects($this->atLeastOnce())
            ->method('findAll')
            ->with('de')
            ->willReturn((static function (): \Generator {
                yield 5001070 => ['name' => 'Abfallentsorgung'];
            })());
        $this->lebenslagenProviderMock
            ->expects($this->atLeastOnce())
            ->method('findById')
            ->with(5001070)
            ->willReturn([
                'id' => 5001070,
                'name' => 'Abfallentsorgung',
                'type' => 'lebenslagen',
                'language' => 'de',
                'data' => '{}',
            ]);

        $this->subject->run($input, $output);

        $repository = $this->repositoryFactory->getRepository(ControllerTypeEnum::LEBENSLAGEN);

        $records = [];
        foreach ($repository->findAll('de') as $record) {
            $records[] = $record;
        }

        self::assertCount(
            1,
            $records,
        );

        self::assertTrue(
            $repository->hasId(5001070),
        );

        self::assertEquals(
            new Record(
                5001070,
                'Abfallentsorgung',
                'lebenslagen',
                'de',
                [
                    'id' => 5001070,
                    'name' => 'Abfallentsorgung',
                    'type' => 'lebenslagen',
                    'language' => 'de',
                    'data' => '{}',
                ],
            ),
            $records[0],
        );
    }
}
