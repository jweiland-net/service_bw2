<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Configuration;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class ExtConfTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    #[Test]
    public function getMandantInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            '',
            $subject->getMandant(),
        );
    }

    #[Test]
    public function setMandantSetsMandant()
    {
        $config = [
            'mandant' => 'jweiland.net',
        ];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'jweiland.net',
            $subject->getMandant(),
        );
    }

    #[Test]
    public function getTokenInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            '',
            $subject->getToken(),
        );
    }

    #[Test]
    public function setTokenSetsToken()
    {
        $config = [
            'token' => 'Bearer abc',
        ];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'Bearer abc',
            $subject->getToken(),
        );
    }

    #[Test]
    public function getBaseUrlInitiallyReturnsDefaultValue()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'https://sgw.service-bw.de:443/rest-v2/api',
            $subject->getBaseUrl(),
        );
    }

    public static function baseUrlDataProvider(): array
    {
        return [
            'Base URL' => ['https://jweiland.net', 'https://jweiland.net'],
            'Base URL trimmed' => ['    https://jweiland.net ', 'https://jweiland.net'],
            'Base URL remove trailing slash' => ['https://jweiland.net/', 'https://jweiland.net'],
        ];
    }

    #[Test]
    #[DataProvider('baseUrlDataProvider')]
    public function setBaseUrlSetsBaseUrl(string $actualBaseUrl, string $expectedBaseUrl)
    {
        $extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $extensionConfigurationMock
            ->expects($this->once())
            ->method('get')
            ->with('service_bw2')
            ->willReturn([
                'baseUrl' => $actualBaseUrl,
            ]);

        $subject = ExtConf::create($extensionConfigurationMock);

        self::assertSame(
            $expectedBaseUrl,
            $subject->getBaseUrl(),
        );
    }

    #[Test]
    public function getAllowedLanguagesInitiallyReturnsPreconfiguredArray()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            [
                'de' => 'de',
                'en' => 'en',
                'fr' => 'fr',
            ],
            $subject->getAllowedLanguages(),
        );
    }

    public static function allowedLanguagesDataProvider(): array
    {
        return [
            'Empty configuration results in default values' => ['', ['de' => 'de', 'en' => 'en', 'fr' => 'fr']],
            'Configuration with language UIDs results in default values' => ['de=1', ['de' => 'de', 'en' => 'en', 'fr' => 'fr']],
            'Single language configuration' => ['de=de', ['de' => 'de']],
            'Multiple language configuration' => ['de=de;fr=fr;en=en', ['de' => 'de', 'fr' => 'fr', 'en' => 'en']],
            'Trailing semicolon will be removed' => ['de=de;', ['de' => 'de']],
            'Spaces will be removed' => ['de = de;   en  = en', ['de' => 'de', 'en' => 'en']],
            'Missing language will be removed' => ['de=de;;fr=fr', ['de' => 'de', 'fr' => 'fr']],
            'Map multiple TYPO3 languages to one Service BW language' => ['de=de;fr=en;gr=en;sp=en', ['de' => 'de', 'fr' => 'en', 'gr' => 'en', 'sp' => 'en']],
        ];
    }

    #[Test]
    #[DataProvider('allowedLanguagesDataProvider')]
    public function setAllowedLanguagesSetsAllowedLanguages(
        string $actualAllowedLanguages,
        array $expectedAllowedLanguages,
    ) {
        $extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $extensionConfigurationMock
            ->expects($this->once())
            ->method('get')
            ->with('service_bw2')
            ->willReturn([
                'allowedLanguages' => $actualAllowedLanguages,
            ]);

        $subject = ExtConf::create($extensionConfigurationMock);

        self::assertSame(
            $expectedAllowedLanguages,
            $subject->getAllowedLanguages(),
        );
    }

    #[Test]
    public function getAgsInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            0,
            $subject->getAgs(),
        );
    }

    #[Test]
    public function setAgsSetsAgs()
    {
        $extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $extensionConfigurationMock
            ->expects($this->once())
            ->method('get')
            ->with('service_bw2')
            ->willReturn([
                'ags' => '083746',
            ]);

        $subject = ExtConf::create($extensionConfigurationMock);

        self::assertSame(
            83746,
            $subject->getAgs(),
        );
    }

    #[Test]
    public function getGebietIdInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            '',
            $subject->getGebietId(),
        );
    }

    #[Test]
    public function setGebietIdSetsGebietId()
    {
        $config = [
            'gebietId' => 'area',
        ];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'area',
            $subject->getGebietId(),
        );
    }
}
