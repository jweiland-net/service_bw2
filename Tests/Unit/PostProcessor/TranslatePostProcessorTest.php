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
use JWeiland\ServiceBw2\PostProcessor\TranslatePostProcessor;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \JWeiland\ServiceBw2\PostProcessor\TranslatePostProcessor
 */
class TranslatePostProcessorTest extends UnitTestCase
{
    /**
     * @var TranslatePostProcessor
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp()
    {
        $this->subject = new TranslatePostProcessor();
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
    public function emptyResponseResultsInEmptyResponse()
    {
        $this->assertSame(
            '',
            $this->subject->process('')
        );
    }

    /**
     * @test
     */
    public function emptyArrayResultsInEmptyArray() {
        $this->assertSame(
            [],
            $this->subject->process([])
        );
    }
}
