<?php

namespace JWeiland\ServiceBw2\Tests\Unit\PostProcessor;

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

use JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor
 */
class JsonPostProcessorTest extends UnitTestCase
{
    /**
     * @var JsonPostProcessor
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp()
    {
        $this->subject = new JsonPostProcessor();
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
    public function emptyStringResultsInEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->process('')
        );
    }

    /**
     * @test
     */
    public function multipleSpacesResultsInEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->process('          ')
        );
    }

    /**
     * @test
     */
    public function emptyIntegerResultsInEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->process(0)
        );
    }

    /**
     * @test
     */
    public function nullResultsInEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->process(null)
        );
    }

    /**
     * @test
     */
    public function processWithInvalidJsonResultsInNull()
    {
        $this->assertNull(
            $this->subject->process('123{ab;}')
        );
    }

    /**
     * @test
     */
    public function processWithoutItemsResultsInFilledArray()
    {
        $this->assertSame(
            [
                0 => 123,
                1 => 321,
            ],
            $this->subject->process('[123,321]')
        );
    }

    /**
     * @test
     */
    public function processWithItemsResultsInFilledArray()
    {
        $this->assertSame(
            [
                0 => ['Vorname' => 'Stefan'],
                1 => ['Vorname' => 'Petra'],
            ],
            $this->subject->process('[{"Vorname":"Stefan"},{"Vorname":"Petra"}]')
        );
    }
}
