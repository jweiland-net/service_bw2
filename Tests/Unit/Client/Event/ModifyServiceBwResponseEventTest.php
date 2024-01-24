<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\Client\Event;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;


/**
 * Test case.
 */
class ModifyServiceBwResponseEventTest extends UnitTestCase
{
    /**
     * @test
     */
    public function getPathGetsPath(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            []
        );

        self::assertSame(
            'path',
            $subject->getPath()
        );
    }

    /**
     * @test
     */
    public function setResponseBodySetsResponseBody(): void
    {
        $array = [
            0 => 'TestValue'
        ];

        $subject = new ModifyServiceBwResponseEvent(
            'path',
            $array
        );

        $subject->setResponseBody($array);

        self::assertSame(
            $array,
            $subject->getResponseBody()
        );
    }

    /**
     * @test
     */
    public function setPaginatedRequestSetsPaginatedRequestToTrue(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            true
        );

        self::assertTrue(
            $subject->isPaginatedRequest()
        );
    }

    /**
     * @test
     */
    public function setPaginatedRequestSetsPaginatedRequestToFalse(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false
        );

        self::assertFalse(
            $subject->isPaginatedRequest()
        );
    }

    /**
     * @test
     */
    public function setLocalizedRequestSetsLocalizedRequestToTrue(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false,
            true
        );

        self::assertTrue(
            $subject->isLocalizedRequest()
        );
    }

    /**
     * @test
     */
    public function setLocalizedRequestSetsLocalizedRequestToFalse(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false,
            false
        );

        self::assertFalse(
            $subject->isLocalizedRequest()
        );
    }
}
