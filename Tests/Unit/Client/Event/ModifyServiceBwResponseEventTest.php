<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\Client\Event;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case.
 */
class ModifyServiceBwResponseEventTest extends UnitTestCase
{
    #[Test]
    public function getPathGetsPath(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
        );

        self::assertSame(
            'path',
            $subject->getPath(),
        );
    }

    #[Test]
    public function setResponseBodySetsResponseBody(): void
    {
        $array = [
            0 => 'TestValue',
        ];

        $subject = new ModifyServiceBwResponseEvent(
            'path',
            $array,
        );

        $subject->setResponseBody($array);

        self::assertSame(
            $array,
            $subject->getResponseBody(),
        );
    }

    #[Test]
    public function setPaginatedRequestSetsPaginatedRequestToTrue(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            true,
        );

        self::assertTrue(
            $subject->isPaginatedRequest(),
        );
    }

    #[Test]
    public function setPaginatedRequestSetsPaginatedRequestToFalse(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false,
        );

        self::assertFalse(
            $subject->isPaginatedRequest(),
        );
    }

    #[Test]
    public function setLocalizedRequestSetsLocalizedRequestToTrue(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false,
            true,
        );

        self::assertTrue(
            $subject->isLocalizedRequest(),
        );
    }

    #[Test]
    public function setLocalizedRequestSetsLocalizedRequestToFalse(): void
    {
        $subject = new ModifyServiceBwResponseEvent(
            'path',
            [],
            false,
            false,
        );

        self::assertFalse(
            $subject->isLocalizedRequest(),
        );
    }
}
