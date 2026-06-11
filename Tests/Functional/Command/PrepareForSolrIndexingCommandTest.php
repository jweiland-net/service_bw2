<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Command;

use ApacheSolrForTypo3\Solr\Domain\Site\Site;
use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use JWeiland\ServiceBw2\Command\PrepareForSolrIndexingCommand;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class PrepareForSolrIndexingCommandTest extends FunctionalTestCase
{
    protected PrepareForSolrIndexingCommand $subject;

    protected RepositoryFactory $repositoryFactory;

    protected array $testExtensionsToLoad = [
        'apache-solr-for-typo3/solr',
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_servicebw2_response.csv');

        $siteRepository = $this->createMock(SiteRepository::class);
        $siteRepository
            ->expects($this->atLeastOnce())
            ->method('getSiteByRootPageId')
            ->with(1)
            ->willReturn($this->createMock(Site::class));
        GeneralUtility::addInstance(SiteRepository::class, $siteRepository);

        $this->repositoryFactory = $this->get(RepositoryFactory::class);

        $this->subject = new PrepareForSolrIndexingCommand(
            $this->createMock(LoggerInterface::class),
            $this->repositoryFactory,
            new ExtConf(allowedLanguages: 'de=de'),
            $this->getConnectionPool(),
        );
    }

    #[Test]
    public function executeWithEmptyLebenslagenWillRemoveAllLebenslagen(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->atLeastOnce())
            ->method('getArgument')
            ->willReturnMap([
                ['request-class', 'Lebenslagen'],
                ['root-page', 1],
                ['solr-index-type', 'tx_servicebw2_lebenslagen'],
            ]);

        $output = $this->createMock(OutputInterface::class);

        $solrIndexServiceMock = $this->createMock(SolrIndexService::class);
        $solrIndexServiceMock
            ->expects($this->once())
            ->method('clearSolrIndexByType')
            ->with(
                'tx_servicebw2_lebenslagen',
                self::isInstanceOf(Site::class),
            );
        $solrIndexServiceMock
            ->expects($this->atLeast(2))
            ->method('indexServiceBWRecord')
            ->with(
                self::isArray(),
                'tx_servicebw2_lebenslagen',
                self::isInstanceOf(Site::class),
            );
        GeneralUtility::addInstance(SolrIndexService::class, $solrIndexServiceMock);

        $this->subject->run($input, $output);
    }
}
