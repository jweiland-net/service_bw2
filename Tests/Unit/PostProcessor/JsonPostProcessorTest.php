<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\PostProcessor;

use JWeiland\ServiceBw2\Exception\HttpResponseException;
use JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
        self::assertSame(
            [],
            $this->subject->process('')
        );
    }

    /**
     * @test
     */
    public function multipleSpacesResultsInEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->process('          ')
        );
    }

    /**
     * @test
     */
    public function emptyIntegerResultsInEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->process(0)
        );
    }

    /**
     * @test
     */
    public function nullResultsInEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->process(null)
        );
    }

    /**
     * @test
     */
    public function processWithInvalidJsonThrowsException()
    {
        $this->expectException(HttpResponseException::class);
        $this->subject->process('123{ab;}');
    }

    /**
     * @test
     */
    public function processWithoutItemsResultsInFilledArray()
    {
        self::assertSame(
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
        self::assertSame(
            [
                0 => ['Vorname' => 'Stefan'],
                1 => ['Vorname' => 'Petra'],
            ],
            $this->subject->process('[{"Vorname":"Stefan"},{"Vorname":"Petra"}]')
        );
    }
}
