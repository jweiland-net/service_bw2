<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Command;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Provider\ProviderFactory;
use JWeiland\ServiceBw2\Domain\Provider\ProviderInterface;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryInterface;
use JWeiland\ServiceBw2\Traits\FilterAllowedLanguagesTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Configurable command to warmup caches of Service BW
 */
#[AsCommand(
    name: 'servicebw:cachewarmup',
    description: 'Warmup the caches of Service BW to improve loading times',
)]
class CacheWarmupCommand extends Command
{
    use FilterAllowedLanguagesTrait;

    public function __construct(
        protected ExtConf $extConf,
        protected ProviderFactory $providerFactory,
        protected RepositoryFactory $repositoryFactory,
        protected LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Configurable command to warmup the caches of Service BW to improve loading times')
            ->addOption(
                'include-lebenslagen',
                null,
                InputOption::VALUE_NONE,
                'Warmup caches of Lebenslagen (Life situations)',
            )
            ->addOption(
                'include-leistungen',
                null,
                InputOption::VALUE_NONE,
                'Warmup caches of Leistungen (Services)',
            )
            ->addOption(
                'include-organisationseinheiten',
                null,
                InputOption::VALUE_NONE,
                'Warmup caches of Organisationseinheiten (Organisational units)',
            )
            ->addOption(
                'locales',
                null,
                InputOption::VALUE_OPTIONAL,
                'Comma-separated list of Service BW language codes to warm up, e.g. "de,en,fr". If omitted or invalid, all allowed Service BW languages will be used.',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->filterAllowedLanguages($input, $this->extConf->getAllowedLanguages()) as $language) {
            $io->section('Language for current cache warmup: ' . $language);

            foreach ($this->providerFactory->getProviders() as $provider) {
                if ($input->getOption('include-' . $provider::CONTROLLER_TYPE)) {
                    $this->warmupType($provider, $language, $io);
                }
            }
        }

        return 0;
    }

    protected function warmupType(
        ProviderInterface $provider,
        string $language,
        SymfonyStyle $io,
    ): void {
        $io->writeln(sprintf(
            '  <info>→</info> Warmup caches for <comment>%s</comment>',
            $provider::CONTROLLER_TYPE,
        ));

        $repository = $this->repositoryFactory->getRepository(
            ControllerTypeEnum::from($provider::CONTROLLER_TYPE),
        );

        $existingIds = array_flip($repository->getAllIds($language));

        try {
            foreach ($provider->findAll($language) as $id => $record) {
                $record = $provider->findById($id, $language);
                if ($record === []) {
                    continue;
                }

                if ($io->isVerbose()) {
                    $io->writeln(sprintf(
                        '    <info>➜</info> ID <comment>%s</comment>, name <comment>%s</comment>',
                        $id,
                        $record['name'] ?? '[no name]',
                    ));
                }

                $repository->addOrUpdate($record, $language);

                unset($existingIds[$id]);
            }

            $this->deleteStaleRecords($existingIds, $language, $repository, $io);
        } catch (\Throwable $throwable) {
            $this->logger->error(
                sprintf(
                    'Cache warmup failed for type "%s" and language "%s": %s',
                    $provider::CONTROLLER_TYPE,
                    $language,
                    $throwable->getMessage(),
                ),
                [
                    'controllerType' => $provider::CONTROLLER_TYPE,
                    'language' => $language,
                    'exceptionClass' => $throwable::class,
                    'exceptionCode' => $throwable->getCode(),
                    'exceptionFile' => $throwable->getFile(),
                    'exceptionLine' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            );

            if ($io->isVerbose()) {
                $io->error(sprintf(
                    'Cache warmup failed for type "%s" and language "%s": %s',
                    $provider::CONTROLLER_TYPE,
                    $language,
                    $throwable->getMessage(),
                ));
            }
        }
    }

    protected function deleteStaleRecords(
        array $staleRecords,
        string $language,
        RepositoryInterface $repository,
        SymfonyStyle $io,
    ): void {
        if ($staleRecords !== []) {
            $io->writeln(sprintf(
                '    <comment>↯</comment> Deleting <comment>%d</comment> stale record(s) for language <comment>%s</comment>: <comment>%s</comment>',
                count($staleRecords),
                $language,
                implode(', ', array_keys($staleRecords)),
            ));

            $repository->deleteIds(array_keys($staleRecords), $language);
        }
    }
}
