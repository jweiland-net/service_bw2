<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Domain\Provider;

use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Provider\LebenslagenProvider;
use JWeiland\ServiceBw2\Domain\Provider\LeistungenProvider;
use JWeiland\ServiceBw2\Domain\Provider\OrganisationseinheitenProvider;
use JWeiland\ServiceBw2\Domain\Provider\ProviderFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class ProviderFactoryTest extends FunctionalTestCase
{
    protected ProviderFactory $subject;

    protected ServiceBwClient|MockObject $serviceBwClientMock;

    protected \ArrayObject $providers;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceBwClientMock = $this->createMock(ServiceBwClient::class);

        $this->providers = new \ArrayObject([
            0 => new LebenslagenProvider($this->serviceBwClientMock),
            1 => new LeistungenProvider($this->serviceBwClientMock),
            2 => new OrganisationseinheitenProvider($this->serviceBwClientMock),
        ]);

        $this->subject = new ProviderFactory($this->providers);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $this->serviceBwClientMock,
            $this->providers,
            $this->subject,
        );
    }

    #[Test]
    public function getProviderWillReturnProvider()
    {
        $controllerType = ControllerTypeEnum::from(
            OrganisationseinheitenProvider::CONTROLLER_TYPE,
        );

        self::assertInstanceOf(
            OrganisationseinheitenProvider::class,
            $this->subject->getProvider($controllerType),
        );
    }

    #[Test]
    public function getProvidersWillReturnRegisteredProviders()
    {
        self::assertSame(
            $this->providers,
            $this->subject->getProviders(),
        );
    }
}
