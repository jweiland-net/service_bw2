<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client\Helper;

use JWeiland\ServiceBw2\Client\Helper\TokenHelper;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TokenHelperTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
    ];

    /**
     * Valid response = return code is 200!
     */
    #[Test]
    public function fetchAndSaveTokenWithValidResponseFetchesAndSavesTheToken(): void
    {
        $requestFactoryMock = $this->createMock(RequestFactory::class);

        $response = new Response();
        $response->getBody()->write('Bearer 123456789');
        $response->getBody()->rewind();

        $requestFactoryMock
            ->expects(self::atLeastOnce())
            ->method('request')
            ->willReturn($response);

        $tokenHelper = new TokenHelper(
            $requestFactoryMock,
            GeneralUtility::makeInstance(Registry::class),
            GeneralUtility::makeInstance(ExtConf::class),
        );
        $tokenHelper->fetchAndSaveToken();

        self::assertEquals(
            'Bearer 123456789',
            GeneralUtility::makeInstance(Registry::class)->get('ServiceBw', 'token'),
        );
    }
}
