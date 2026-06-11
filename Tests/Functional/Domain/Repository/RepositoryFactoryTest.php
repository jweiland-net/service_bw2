<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Domain\Repository;

use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class RepositoryFactoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    #[Test]
    public function getRepositoryWillThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find repository for selected controller type "organisationseinheiten"!');

        $subject = new RepositoryFactory([
            $this->get(LebenslagenRepository::class),
            $this->get(LeistungenRepository::class),
        ]);

        $subject->getRepository(ControllerTypeEnum::ORGANISATIONSEINHEITEN);
    }

    #[Test]
    public function getRepositoryWillReturnRepository(): void
    {
        $subject = $this->get(RepositoryFactory::class);

        self::assertInstanceOf(
            LebenslagenRepository::class,
            $subject->getRepository(ControllerTypeEnum::LEBENSLAGEN),
        );
    }
}
