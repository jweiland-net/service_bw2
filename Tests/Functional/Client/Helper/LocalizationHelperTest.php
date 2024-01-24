<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client;

use JWeiland\ServiceBw2\Client\Helper\LocalizationHelper;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class LocalizationHelperTest extends FunctionalTestCase
{
    protected LocalizationHelper $subject;

    /**
     * @var string[]
     */
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new LocalizationHelper(
            new ExtConf()
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->extConf,
            $this->subject
        );

        parent::tearDown();
    }

    public function frontendLanguageDataProvider(): array
    {
        return [
            ['en', 'en', 'get allowed language'],
            ['de', 'de', 'get allowed language'],
            ['fr', 'fr', 'get allowed language'],
            ['xy', 'de', 'get default language because page locale is not allowed'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider frontendLanguageDataProvider
     */
    public function getFrontendLanguageIsoCodeReturnsIsoCode(string $locale, string $expected, string $message): void
    {
        $siteLanguage = new SiteLanguage(1, $locale, new Uri('/' . $locale), ['iso-639-1' => $locale]);
        $GLOBALS['TYPO3_REQUEST'] = new ServerRequest();
        $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST']->withAttribute('language', $siteLanguage);

        self::assertEquals(
            $expected,
            $this->subject->getFrontendLanguageIsoCode(),
            $message
        );
    }
}
