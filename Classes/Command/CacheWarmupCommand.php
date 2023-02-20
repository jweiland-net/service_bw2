<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Command;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Request\EntityRequestInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Configurable command to warmup caches of Service BW
 */
class CacheWarmupCommand extends Command
{
    protected ExtConf $extConf;

    protected OutputInterface $output;

    public function __construct(ExtConf $extConf)
    {
        parent::__construct();

        $this->extConf = $extConf;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Configurable command to warmup the caches of Service BW to improve loading times')
            ->addOption(
                'include-lebenslagen',
                null,
                InputOption::VALUE_OPTIONAL,
                'Warmup caches of Lebenslagen (Life situations)',
                true
            )
            ->addOption(
                'include-leistungen',
                null,
                InputOption::VALUE_OPTIONAL,
                'Warmup caches of Leistungen (Services)',
                true
            )
            ->addOption(
                'include-organisationseinheiten',
                null,
                InputOption::VALUE_OPTIONAL,
                'Warmup caches of Organisationseinheiten (Organisational units)',
                true
            )
            ->addOption(
                'locales',
                null,
                InputOption::VALUE_OPTIONAL,
                'Comma separated list of locales for warmup e.g. "de,en,fr". All allowed languages will be used by default!'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $languages = GeneralUtility::trimExplode(',', $input->getOption('locales'), true)
            ?: array_keys($this->extConf->getAllowedLanguages());

        foreach ($languages as $language) {
            $output->writeln('Language for further requests: ' . $language);
            // We're using the ServerRequest for the SiteLanguage only! Maybe use an alternative way in later versions
            $GLOBALS['TYPO3_REQUEST'] = GeneralUtility::makeInstance(ServerRequest::class);
            $siteLanguage = GeneralUtility::makeInstance(
                SiteLanguage::class,
                1,
                $language,
                GeneralUtility::makeInstance(Uri::class, '/' . $language),
                ['iso-639-1' => $language]
            );
            $GLOBALS['TYPO3_REQUEST']->withAttribute('language', $siteLanguage);

            $types = ['lebenslagen', 'leistungen', 'organisationseinheiten'];
            foreach ($types as $type) {
                if ($input->getOption('include-' . $type)) {
                    $this->warmupType($type);
                }
            }
        }

        return 0;
    }

    protected function warmupType(string $type): void
    {
        $this->output->writeln('Warmup caches for "' . $type . '"');
        $requestClass = $this->getEntityRequestType($type);
        if ($requestClass === null) {
            return;
        }

        $allRecords = $requestClass->findAll();
        $progressBar = new ProgressBar($this->output, count($allRecords));
        $progressBar->start();
        foreach ($allRecords as $record) {
            $requestClass->findById($record['id']);
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->output->writeln('');
    }

    protected function getEntityRequestType(string $type): ?EntityRequestInterface
    {
        /** @var EntityRequestInterface $requestClass */
        $requestClass = GeneralUtility::makeInstance('JWeiland\\ServiceBw2\\Request\\Portal\\' . ucfirst($type));

        return $requestClass;
    }
}
