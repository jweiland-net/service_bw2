<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use JWeiland\ServiceBw2\Helper\LeistungenHelper;
use JWeiland\ServiceBw2\Listener\LeistungenListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class LeistungenListenerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    public static function eventDataProvider(): array
    {
        return [
            [
                new ModifyServiceBwResponseEvent('/portal/leistungsdetails/1234', []),
                ['hasFormulare' => false, 'hasProzesse' => false],
            ],
            [
                new ModifyServiceBwResponseEvent('/portal/leistungsdetails/1234', ['formulare' => ['abc' => 'def']]),
                ['hasFormulare' => true, 'hasProzesse' => false],
            ],
            [
                new ModifyServiceBwResponseEvent('/portal/leistungsdetails/1234', ['prozesse' => ['abc' => 'def']]),
                ['hasFormulare' => false, 'hasProzesse' => true],
            ],
            [
                new ModifyServiceBwResponseEvent('/portal/leistungsdetails/1234', ['formulare' => ['abc' => 'def'], 'prozesse' => ['abc' => 'def']]),
                ['hasFormulare' => true, 'hasProzesse' => true],
            ],
            [
                new ModifyServiceBwResponseEvent('/portal/leistungsdetails/1234', ['formulare' => [], 'prozesse' => []]),
                ['hasFormulare' => false, 'hasProzesse' => false],
            ],
        ];
    }

    #[Test]
    #[DataProvider('eventDataProvider')]
    public function invokeSavesAdditionalData(ModifyServiceBwResponseEvent $event, array $expectedResult): void
    {
        $leistungenHelperMock = $this->createMock(LeistungenHelper::class);
        $leistungenHelperMock
            ->expects(self::atLeastOnce())
            ->method('saveAdditionalData')
            ->with(self::equalTo(1234));

        $leistungenListener = new LeistungenListener($leistungenHelperMock);
        $leistungenListener($event);
    }
}
