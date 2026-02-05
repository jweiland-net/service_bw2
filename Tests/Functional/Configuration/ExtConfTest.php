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
    public function getUsernameInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            '',
            $subject->getUsername(),
        );
    }

    #[Test]
    public function setUsernameSetsUsername()
    {
        $config = [
            'username' => 'jweiland',
        ];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'jweiland',
            $subject->getUsername(),
        );
    }

    #[Test]
    public function getPasswordInitiallyReturnsEmptyString()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            '',
            $subject->getPassword(),
        );
    }

    #[Test]
    public function setPasswordSetsPassword()
    {
        $config = [
            'password' => 'crypted',
        ];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'crypted',
            $subject->getPassword(),
        );
    }

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
    public function getBaseUrlInitiallyReturnsDefaultValue()
    {
        $config = [];
        $subject = new ExtConf(...$config);

        self::assertSame(
            'https://sgw.service-bw.de:443',
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
            ->expects(self::once())
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
                'de' => 0,
                'en' => 1,
                'fr' => 2,
            ],
            $subject->getAllowedLanguages(),
        );
    }

    public static function allowedLanguagesDataProvider(): array
    {
        return [
            'Empty' => ['', ['de' => 0, 'en' => 1, 'fr' => 2]],
            'Single Language' => ['de=1', ['de' => 1]],
            'Multiple Languages' => ['de=0;fr=1;en=2', ['de' => 0, 'fr' => 1, 'en' => 2]],
            'Multiple Languages with trailing ;' => ['de=0;pl=1;sp=2;', ['de' => 0, 'pl' => 1, 'sp' => 2]],
            'Use default on non trimmed values' => ['de = 1;   en  = 2', ['de' => 0, 'en' => 1, 'fr' => 2]],
            'Use default on empty language settings' => ['de=1;;fr=3;', ['de' => 0, 'en' => 1, 'fr' => 2]],
            'Use default on missing sys_language_uid' => ['de', ['de' => 0, 'en' => 1, 'fr' => 2]],
            'Use default on wrong lang string' => ['deu', ['de' => 0, 'en' => 1, 'fr' => 2]],
            'Use default on vise versa configuration' => ['1=de;3=fr;', ['de' => 0, 'en' => 1, 'fr' => 2]],
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
            ->expects(self::once())
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
            ->expects(self::once())
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
