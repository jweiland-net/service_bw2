<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client;

use JWeiland\ServiceBw2\Client\Helper\LocalizationHelper;
use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ServiceBwClientTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        GeneralUtility::makeInstance(Registry::class)
            ->set('ServiceBw', 'token', '123456');
    }

    public function requestVariantsDataProvider(): array
    {
        return [
            'default query' => [
                false,
                [],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                ],
            ],
            'default paginated query' => [
                true,
                [],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'page' => 0,
                    'pageSize' => 1000,
                ],
            ],
            'query with additional parameters' => [
                false,
                [
                    'coca' => 'cola',
                ],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'coca' => 'cola',
                ],
            ],
            'paginated query with additional parameters' => [
                false,
                [
                    'pepsi' => 'cola',
                ],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'pepsi' => 'cola',
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('requestVariantsDataProvider')]
    public function requestAddsQueryParameters(
        bool $isPaginatedRequest,
        array $getParameters,
        array $expectedQuery,
    ): void {
        $extConf = new ExtConf();
        $extConf->setMandant('testMandant');
        $extConf->setAgs('1234');
        $extConf->setGebietId('testGebietId');

        $responseBody = [
            0 => [
                'hello' => 'world',
            ],
        ];
        if ($isPaginatedRequest) {
            $responseBody = ['items' => $responseBody];
        }

        $response = new Response();
        $response->getBody()->write(json_encode($responseBody));
        $response->getBody()->rewind();

        $requestFactoryMock = $this->createMock(RequestFactory::class);
        $requestFactoryMock
            ->method('request')
            ->with(
                self::anything(),
                self::equalTo('GET'),
                self::callback(function ($argument) use ($expectedQuery) {
                    try {
                        Factory::getInstance()
                            ->getComparatorFor($expectedQuery, $argument['query'])
                            ->assertEquals($expectedQuery, $argument['query']);
                    } catch (ComparisonFailure $comparisonFailure) {
                        echo $comparisonFailure->getDiff();
                        return false;
                    }

                    return true;
                }),
            )
            ->willReturn($response);

        $serviceBwClient = new ServiceBwClient(
            $requestFactoryMock,
            GeneralUtility::makeInstance(Registry::class),
            $extConf,
            GeneralUtility::makeInstance(EventDispatcher::class),
            GeneralUtility::makeInstance(LocalizationHelper::class),
            GeneralUtility::makeInstance(TokenHelper::class),
        );

        $result = $serviceBwClient->request(
            (string)time(),
            $getParameters,
            true,
            $isPaginatedRequest,
        );

        self::assertEquals(
            [
                0 => [
                    'hello' => 'world',
                ],
            ],
            $result,
        );
    }
}
