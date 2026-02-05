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
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ServiceBwClientTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $request = (new ServerRequest('https://example.com/typo3/'))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE)
            ->withAttribute('route', new Route('path', ['packageName' => 'typo3/cms-backend']));
        $GLOBALS['TYPO3_REQUEST'] = $request;

        GeneralUtility::makeInstance(Registry::class)
            ->set('ServiceBw', 'token', '123456');
    }

    public static function requestVariantsDataProvider(): array
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
        $extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('service_bw2')
            ->willReturn([
                'mandant' => 'testMandant',
                'ags' => '1234',
                'gebietId' => 'testGebietId',
            ]);

        $extConf = ExtConf::create($extensionConfigurationMock);

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
            ->expects(self::atLeastOnce())
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
            self::createStub(TokenHelper::class),
            self::createStub(VariableFrontend::class),
            self::createStub(Logger::class),
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
