<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client;

use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ServiceBwClientTest extends FunctionalTestCase
{
    use ProphecyTrait;

    protected $testExtensionsToLoad = ['typo3conf/ext/service_bw2'];

    protected function setUp(): void
    {
        parent::setUp();
        GeneralUtility::makeInstance(Registry::class)->set('ServiceBw', 'token', '123456');
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
                    'gebietId' => 'testGebietId'
                ]
            ],
            'default paginated query' => [
                true,
                [],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'page' => 0,
                    'pageSize' => 1000
                ]
            ],
            'query with additional parameters' => [
                false,
                [
                    'coca' => 'cola'
                ],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'coca' => 'cola'
                ]
            ],
            'paginated query with additional parameters' => [
                false,
                [
                    'pepsi' => 'cola'
                ],
                [
                    'mandantId' => 'testMandant',
                    'gebietAgs' => 1234,
                    'gebietId' => 'testGebietId',
                    'pepsi' => 'cola'
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider requestVariantsDataProvider
     * @param bool $isPaginatedRequest
     * @param array $getParameters
     * @param array $expectedQuery
     */
    public function requestAddsQueryParameters(
        bool $isPaginatedRequest,
        array $getParameters,
        array $expectedQuery
    ): void {
        $extConf = new ExtConf();
        $extConf->setMandant('testMandant');
        $extConf->setAgs('1234');
        $extConf->setGebietId('testGebietId');

        $responseBody = [
            0 => [
                'hello' => 'world'
            ]
        ];
        if ($isPaginatedRequest) {
            $responseBody = ['items' => $responseBody];
        }

        $response = new Response();
        $response->getBody()->write(json_encode($responseBody));
        $response->getBody()->rewind();

        $requestFactoryProphecy = $this->prophesize(RequestFactory::class);
        $requestFactoryProphecy
            ->request(
                Argument::any(),
                Argument::exact('GET'),
                Argument::that(static function ($argument) use ($expectedQuery) {
                    try {
                        Factory::getInstance()
                            ->getComparatorFor($expectedQuery, $argument['query'])
                            ->assertEquals($expectedQuery, $argument['query']);
                    } catch (ComparisonFailure $comparisonFailure) {
                        echo $comparisonFailure->getDiff();
                        return false;
                    }
                    return true;
                })
            )
            ->willReturn($response)
            ->shouldBeCalled();

        $serviceBwClient = new ServiceBwClient(
            $requestFactoryProphecy->reveal(),
            GeneralUtility::makeInstance(Registry::class),
            GeneralUtility::makeInstance(TokenHelper::class),
            $extConf,
            GeneralUtility::makeInstance(EventDispatcher::class)
        );

        $result = $serviceBwClient->request(
            (string)time(),
            $getParameters,
            true,
            $isPaginatedRequest
        );

        self::assertEquals(
            [
                0 => [
                    'hello' => 'world'
                ]
            ],
            $result
        );
    }
}
