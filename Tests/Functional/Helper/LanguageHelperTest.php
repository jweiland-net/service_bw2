<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Helper;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Helper\LanguageHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class LanguageHelperTest extends FunctionalTestCase
{
    protected LanguageHelper $subject;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new LanguageHelper(
            new ExtConf(
                '123',
                'abc123',
                '',
                'de=de;en=en;fr=en',
                12,
                '',
            ),
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $this->subject,
        );
    }

    public static function languageDataProvider(): array
    {
        return [
            'TYPO3 language de will resolve to Service BW language de' => ['de_DE.utf8', 'de'],
            'TYPO3 language en will resolve to Service BW language en' => ['en_US.utf8', 'en'],
            'TYPO3 language fr will resolve to Service BW language en' => ['fr_FR.utf8', 'en'],
        ];
    }

    #[Test]
    #[DataProvider(
        methodName: 'languageDataProvider',
    )]
    public function getServiceBwLanguageCodeFromRequestWillReturnServiceBwLanguageCode(
        string $typo3Locale,
        string $serviceBwLanguage,
    ) {
        $language = new SiteLanguage(
            2,
            $typo3Locale,
            new Uri('https://example.com/'),
            [],
        );

        $request = (new ServerRequest())->withAttribute('language', $language);

        self::assertSame(
            $serviceBwLanguage,
            $this->subject->getServiceBwLanguageCodeFromRequest($request),
        );
    }

    #[Test]
    public function getServeBwLanguageCodeByTypo3LanguageCodeWillReturnServiceBwLanguageCode()
    {
        self::assertSame(
            'de',
            $this->subject->getServeBwLanguageCodeByTypo3LanguageCode('de'),
        );

        self::assertSame(
            'en',
            $this->subject->getServeBwLanguageCodeByTypo3LanguageCode('en'),
        );

        self::assertSame(
            'fr',
            $this->subject->getServeBwLanguageCodeByTypo3LanguageCode('en'),
        );
    }

    #[Test]
    public function getDefaultServiceBwLanguageCodeWillReturnDefaultServiceBwLanguageCode()
    {
        self::assertSame(
            'de',
            $this->subject->getDefaultServiceBwLanguageCode(),
        );
    }

    #[Test]
    public function getTypo3LanguageCodeFromRequestWillReturnTypo3LanguageCode()
    {
        $language = new SiteLanguage(
            2,
            'de_DE.utf8',
            new Uri('https://example.com/'),
            [],
        );

        $request = (new ServerRequest())->withAttribute('language', $language);

        self::assertSame(
            'de',
            $this->subject->getTypo3LanguageCodeFromRequest($request)
        );
    }
}
