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
use JWeiland\ServiceBw2\EventListener\LeistungenEventListener;
use JWeiland\ServiceBw2\Helper\LeistungenHelper;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class LeistungenHelperTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    #[Test]
    public function saveAdditionalDataWritesDataToCache(): void
    {
        $leistungenMock = self::createStub(Leistungen::class);
        $data = ['hello' => 'world', 'time' => time()];
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenHelper = new LeistungenHelper($leistungenMock, $cache);
        $leistungenHelper->saveAdditionalData(1234, $data);

        self::assertEquals(
            $data,
            $cache->get('leistung_1234'),
        );
    }

    #[Test]
    public function getAdditionalDataCallsFindByIdIfCacheIsNotSet(): void
    {
        $leistungenMock = self::createMock(Leistungen::class);
        $leistungenMock
            ->expects(self::atLeastOnce())
            ->method('findById')
            ->with(self::equalTo(1234))
            ->willReturn(['got' => 'called']);

        $leistungenHelper = new LeistungenHelper(
            $leistungenMock,
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
        );
        $leistungenHelper->getAdditionalData(1234);
    }

    #[Test]
    public function getAdditionalDataReturnsArrayFromFindByIdIfCacheIsNotSet(): void
    {
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenMock = self::createMock(Leistungen::class);
        $leistungenMock
            ->expects(self::atLeastOnce())
            ->method('findById')
            ->with(self::equalTo(1234))
            ->willReturnCallback(function () use ($cache, $leistungenMock) {
                $leistungenListener = new LeistungenEventListener(new LeistungenHelper(
                    $leistungenMock, // Using $this refers to the mock object itself
                    $cache,
                ));
                $leistungenListener(new ModifyServiceBwResponseEvent(
                    '/portal/leistungsdetails/1234',
                    [],
                ));
                return [];
            });

        $leistungenHelper = new LeistungenHelper(
            $leistungenMock,
            $cache,
        );

        self::assertEquals(
            ['hasFormulare' => false, 'hasProzesse' => false],
            $leistungenHelper->getAdditionalData(1234),
        );
    }

    #[Test]
    public function getAdditionalDataReturnsEmptyArrayWithFetchIfMissingFalseAndCacheIsNotSet(): void
    {
        $leistungenMock = self::createStub(Leistungen::class);
        $leistungenHelper = new LeistungenHelper(
            $leistungenMock,
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
        );
        self::assertEquals(
            [],
            $leistungenHelper->getAdditionalData(1234, false),
        );
    }

    #[Test]
    public function getAdditionalDataReturnsPreviouslySavedAdditionalDataFromCache(): void
    {
        $leistungenMock = self::createStub(Leistungen::class);
        $data = ['important' => 'data'];
        $leistungenHelper = new LeistungenHelper(
            $leistungenMock,
            new VariableFrontend(__FUNCTION__, new TransientMemoryBackend('')),
        );
        $leistungenHelper->saveAdditionalData(1234, $data);

        self::assertEquals(
            $data,
            $leistungenHelper->getAdditionalData(1234),
        );
    }
}
