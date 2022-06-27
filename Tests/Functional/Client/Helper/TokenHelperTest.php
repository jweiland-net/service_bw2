<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client\Helper;

use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TokenHelperTest extends FunctionalTestCase
{
    use ProphecyTrait;

    protected $testExtensionsToLoad = ['typo3conf/ext/service_bw2'];

    /**
     * Valid response = return code is 200!
     *
     * @test
     */
    public function fetchAndSaveTokenWithValidResponseFetchesAndSavesTheToken(): void
    {
        $requestFactoryProphecy = $this->prophesize(RequestFactory::class);

        $response = new Response();
        $response->getBody()->write('Bearer 123456789');
        $response->getBody()->rewind();

        $requestFactoryProphecy
            ->request(Argument::any(), Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($response);

        $tokenHelper = new TokenHelper(
            $requestFactoryProphecy->reveal(),
            GeneralUtility::makeInstance(Registry::class),
            GeneralUtility::makeInstance(ExtConf::class)
        );
        $tokenHelper->fetchAndSaveToken();

        self::assertEquals(
            'Bearer 123456789',
            GeneralUtility::makeInstance(Registry::class)->get('ServiceBw', 'token')
        );
    }
}
