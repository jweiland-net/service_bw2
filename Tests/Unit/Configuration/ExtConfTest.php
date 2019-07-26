<?php

namespace JWeiland\ServiceBw2\Tests\Unit\Configuration;

/*
 * This file is part of the service_bw2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use JWeiland\ServiceBw2\Configuration\ExtConf;

/**
 * Test case for class \JWeiland\ServiceBw2\Configuration\ExtConf
 */
class ExtConfTest extends UnitTestCase
{
    /**
     * @var ExtConf
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp()
    {
        $this->subject = new ExtConf();
    }

    /**
     * tear down.
     */
    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getUsernameInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getUsername()
        );
    }

    /**
     * @test
     */
    public function setUsernameWithIntegerResultsInString()
    {
        $this->subject->setUsername(123);
        $this->assertSame('123', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function setUsernameWithBooleanResultsInString()
    {
        $this->subject->setUsername(TRUE);
        $this->assertSame('1', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function getPasswordInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function setPasswordWithIntegerResultsInString()
    {
        $this->subject->setPassword(123);
        $this->assertSame('123', $this->subject->getPassword());
    }

    /**
     * @test
     */
    public function setPasswordWithBooleanResultsInString()
    {
        $this->subject->setPassword(TRUE);
        $this->assertSame('1', $this->subject->getPassword());
    }

    /**
     * @test
     */
    public function getMandantInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getMandant()
        );
    }

    /**
     * @test
     */
    public function setMandantWithIntegerResultsInString()
    {
        $this->subject->setMandant(123);
        $this->assertSame('123', $this->subject->getMandant());
    }

    /**
     * @test
     */
    public function setMandantWithBooleanResultsInString()
    {
        $this->subject->setMandant(TRUE);
        $this->assertSame('1', $this->subject->getMandant());
    }

    /**
     * @test
     */
    public function getBaseUrlInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getBaseUrl()
        );
    }

    /**
     * @test
     */
    public function setBaseUrlWithIntegerResultsInString()
    {
        $this->subject->setBaseUrl(123);
        $this->assertSame('123', $this->subject->getBaseUrl());
    }

    /**
     * @test
     */
    public function setBaseUrlWithBooleanResultsInString()
    {
        $this->subject->setBaseUrl(TRUE);
        $this->assertSame('1', $this->subject->getBaseUrl());
    }

    /**
     * @test
     */
    public function getAllowedLanguagesInitiallyReturnsEmptyArray()
    {
        $this->assertSame(
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

        $this->assertSame(
            [],
            $this->subject->getAllowedLanguages()
        );
    }

    /**
     * @test
     */
    public function setAllowedLanguagesWithIntegerResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages(123);
        $this->assertSame([], $this->subject->getAllowedLanguages());
    }

    /**
     * @test
     */
    public function setAllowedLanguagesWithInvalidStringResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages('12?42.fr');
        $this->assertSame([], $this->subject->getAllowedLanguages());
    }

    /**
     * @test
     */
    public function setAllowedLanguagesWithViceVersaConfigurationResultsInEmptyArray()
    {
        $this->subject->setAllowedLanguages('0=de;1=en;2=fr');
        $this->assertSame([], $this->subject->getAllowedLanguages());
    }

    /**
     * @test
     */
    public function setAllowedLanguageResultsInLanguageArray()
    {
        $this->subject->setAllowedLanguages('de=0');
        $this->assertSame(
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
        $this->assertSame(
            [
                'de' => 0,
                'en' => 1,
                'fr' => 2
            ],
            $this->subject->getAllowedLanguages()
        );
    }
}
