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
    protected array $types = [
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

    protected ExtConf $extConf;

    protected OutputInterface $output;

    public function injectExtConf(ExtConf $extConf): void
    {
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
        
        foreach ($this->getLanguages($input) as $language2letterIsoCode) {
            $output->writeln('Language for further requests: ' . $language2letterIsoCode);

            // We're using the ServerRequest for the SiteLanguage only! Maybe use an alternative way in later versions
            $GLOBALS['TYPO3_REQUEST'] = $this->getTypo3Request($language2letterIsoCode);

            foreach ($this->types as $type) {
                if ($input->getOption($type['option'])) {
                    $this->warmupType($type['class']);
                }
            }
        }

        return 0;
    }

    /**
     * @return string[] Array of 2-letter language ISO codes
     */
    protected function getLanguages(InputInterface $input): array
    {
        $configuredLanguages = GeneralUtility::trimExplode(
            ',',
            $input->getOption('locales'),
            true
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
            ['iso-639-1' => $language2letterIsoCode]
        );

        return $typo3Request->withAttribute('language', $siteLanguage);
    }

    protected function warmupType(string $className): void
    {
        $this->output->writeln('Warmup caches for "' . $className . '"');

        $request = $this->getRequestObject($className);
        $allRecords = $request->findAll();

        $progressBar = new ProgressBar($this->output, count($allRecords));
        $progressBar->start();
        foreach ($allRecords as $record) {
            $request->findById($record['id']);
            $progressBar->advance();
        }
        $progressBar->finish();

        $this->output->writeln('');
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
