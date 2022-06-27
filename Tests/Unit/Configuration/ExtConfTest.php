<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\Configuration;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \JWeiland\ServiceBw2\Configuration\ExtConf
 */
class ExtConfTest extends UnitTestCase
{
    use ExtensionConfigurationMockTrait;

    /**
     * @var ExtConf
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp(): void
    {
        $this->addExtensionConfigurationMockToGeneralUtilityInstances();
        $this->subject = GeneralUtility::makeInstance(ExtConf::class);
    }

    /**
     * tear down.
     */
    public function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getUsernameInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getUsername()
        );
    }

    /**
     * @test
     */
    public function setUsernameSetsUsername()
    {
        $this->subject->setUsername('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getUsername()
        );
    }

    /**
     * @test
     */
    public function getPasswordInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function setPasswordSetsPassword()
    {
        $this->subject->setPassword('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function getMandantInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getMandant()
        );
    }

    /**
     * @test
     */
    public function setMandantSetsMandant()
    {
        $this->subject->setMandant('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getMandant()
        );
    }

    /**
     * @test
     */
    public function getBaseUrlInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getBaseUrl()
        );
    }

    /**
     * @test
     */
    public function setBaseUrlSetsBaseUrl()
    {
        $this->subject->setBaseUrl('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getBaseUrl()
        );
    }

    /**
     * @test
     */
    public function getAllowedLanguagesInitiallyReturnsEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->getAllowedLanguages()
        );
    }

    /**
     * @test
     */
    public function setEmptyLanguagesResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages('');

        self::assertSame(
            [],
            $this->subject->getAllowedLanguages()
        );
    }

    /**
     * @test
     */
    public function setAllowedLanguagesWithInvalidStringResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages('12?42.fr');
        self::assertSame([], $this->subject->getAllowedLanguages());
    }

    /**
     * @test
     */
    public function setAllowedLanguagesWithViceVersaConfigurationResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages('0=de;1=en;2=fr');
        self::assertSame([], $this->subject->getAllowedLanguages());
    }

    /**
     * @test
     */
    public function setAllowedLanguageResultsInLanguageArray()
    {
        $this->subject->setAllowedLanguages('de=0');
        self::assertSame(
            [
                'de' => 0
            ],
            $this->subject->getAllowedLanguages()
        );
    }

    /**
     * @test
     */
    public function setAllowedLanguagesResultsInLanguageArray()
    {
        $this->subject->setAllowedLanguages('de=0;en=1;fr=2');
        self::assertSame(
            [
                'de' => 0,
                'en' => 1,
                'fr' => 2
            ],
            $this->subject->getAllowedLanguages()
        );
    }
}
