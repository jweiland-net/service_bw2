<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client\Request\Portal;

use JWeiland\ServiceBw2\Client\Request\Portal\Organisationseinheiten;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class OrganisationseinheitenTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    #[Test]
    public function getUrlWillReturnUrl()
    {
        $subject = new Organisationseinheiten();

        self::assertSame(
            '/portal/organisationseinheiten',
            $subject->getUrl(),
        );
    }

    #[Test]
    public function getQueryWillReturnQuery()
    {
        $subject = new Organisationseinheiten();

        self::assertSame(
            [
                'sortDirection' => 'asc',
                'sortProperty' => 'name',
            ],
            $subject->getQuery(),
        );
    }

    #[Test]
    public function getHeadersWillReturnHeaders()
    {
        $subject = new Organisationseinheiten();

        self::assertSame(
            [
                'Accept' => 'application/json',
            ],
            $subject->getHeaders(),
        );
    }

    #[Test]
    public function getBodyWillReturnBody()
    {
        $subject = new Organisationseinheiten();

        self::assertNull(
            $subject->getBody(),
        );
    }
}
