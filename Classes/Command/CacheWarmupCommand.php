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
use JWeiland\ServiceBw2\Request\EntityRequestInterface;
use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use Psr\Http\Message\ServerRequestInterface;
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
    protected const TYPES = [
        'lebenslagen' => [
            'option' => 'include-lebenslagen',
            'class' => Lebenslagen::class,
        ],
        'leistungen' => [
            'option' => 'include-leistungen',
            'class' => Leistungen::class,
        ],
        'organisationseinheiten' => [
            'option' => 'include-organisationseinheiten',
            'class' => Organisationseinheiten::class,
        ],
    ];

    public function __construct(
        protected ExtConf $extConf,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Warm up Service BW caches (API prefetch) to improve frontend loading times')
            ->setHelp(
                <<<'HELP'
Warms up Service BW caches by pre-fetching records via the Service BW API. This reduces waiting time for website visitors,
because without warmup the first frontend request may take noticeably longer while data is fetched and cached.

Select at least one cache type (or use --all). If you also run "servicebw:preparesolrindex", run this warmup first so the
indexer can benefit from already warmed caches.

Examples:
  servicebw:cachewarmup --include-lebenslagen
  servicebw:cachewarmup --include-leistungen --locales=de,en
  servicebw:cachewarmup --all
HELP
            )
            ->addOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Warm up all cache types',
            )
            ->addOption(
                'include-lebenslagen',
                'leb',
                InputOption::VALUE_NONE,
                'Warm up caches for Lebenslagen (life situations)',
            )
            ->addOption(
                'include-leistungen',
                'lei',
                InputOption::VALUE_NONE,
                'Warm up caches for Leistungen (services)',
            )
            ->addOption(
                'include-organisationseinheiten',
                'org',
                InputOption::VALUE_NONE,
                'Warm up caches for Organisationseinheiten (organisational units)',
            )
            ->addOption(
                'locales',
                'loc',
                InputOption::VALUE_OPTIONAL,
                'Comma-separated list of locales to warm up (e.g. "de,en"). Defaults to all allowed languages.',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $warmupAll = (bool)$input->getOption('all');

        $selectedAnyType = $warmupAll;
        foreach (self::TYPES as $type) {
            if ($input->getOption($type['option'])) {
                $selectedAnyType = true;
                break;
            }
        }

        if (!$selectedAnyType) {
            $output->writeln('<error>Please select at least one cache type (e.g. --include-leistungen) or use --all.</error>');
            return Command::INVALID;
        }

        foreach ($this->getLanguages($input) as $language2letterIsoCode) {
            $output->writeln('Language for further requests: ' . $language2letterIsoCode);

            // We're using the ServerRequest for the SiteLanguage only! Maybe use an alternative way in later versions
            $GLOBALS['TYPO3_REQUEST'] = $this->getTypo3Request($language2letterIsoCode);

            foreach (self::TYPES as $type) {
                if ($warmupAll || $input->getOption($type['option'])) {
                    $this->warmupType($type['class'], $output);
                }
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @return string[] Array of 2-letter language ISO codes
     */
    protected function getLanguages(InputInterface $input): array
    {
        $configuredLanguages = GeneralUtility::trimExplode(
            ',',
            (string)$input->getOption('locales'),
            true,
        );

        // If no languages are configured for command, use all allowed languages configured in extension settings
        return $configuredLanguages ?: array_keys($this->extConf->getAllowedLanguages());
    }

    protected function getTypo3Request(string $language2letterIsoCode): ServerRequestInterface
    {
        $typo3Request = GeneralUtility::makeInstance(ServerRequest::class);

        $siteLanguage = GeneralUtility::makeInstance(
            SiteLanguage::class,
            1,
            $language2letterIsoCode,
            GeneralUtility::makeInstance(Uri::class, '/' . $language2letterIsoCode),
            ['iso-639-1' => $language2letterIsoCode],
        );

        return $typo3Request->withAttribute('language', $siteLanguage);
    }

    protected function warmupType(string $className, OutputInterface $output): void
    {
        $output->writeln('Warmup caches for "' . $className . '"');

        $request = $this->getRequestObject($className);
        $allRecords = $request->findAll();

        $progressBar = new ProgressBar($output, count($allRecords));
        $progressBar->start();
        foreach ($allRecords as $record) {
            $request->findById($record['id']);
            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln('');
    }

    protected function getRequestObject(string $className): EntityRequestInterface
    {
        if (
            class_exists($className)
            && ($requestObject = GeneralUtility::makeInstance($className))
            && $requestObject instanceof EntityRequestInterface
        ) {
            return $requestObject;
        }

        throw new \InvalidArgumentException('Invalid classname ' . $className . ' for request detected');
    }
}
