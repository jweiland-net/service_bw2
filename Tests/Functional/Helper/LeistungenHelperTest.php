<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use JWeiland\ServiceBw2\Helper\LeistungenHelper;
use JWeiland\ServiceBw2\Listener\LeistungenListener;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
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
        'jweiland/service-bw2'
    ];

    /**
     * @test
     */
    public function saveAdditionalDataWritesDataToCache(): void
    {
        $leistungenMock = $this->createMock(Leistungen::class);
        $data = ['hello' => 'world', 'time' => time()];
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenHelper = new LeistungenHelper($cache, $leistungenMock);
        $leistungenHelper->saveAdditionalData(1234, $data);

        self::assertEquals(
            $data,
            $cache->get('leistung_1234')
        );
    }

    /**
     * @test
     */
    public function getAdditionalDataCallsFindByIdIfCacheIsNotSet(): void
    {
        $leistungenMock = $this->createMock(Leistungen::class);
        $leistungenMock
            ->expects(self::atLeastOnce())
            ->method('findById')
            ->with($this->equalTo(1234))
            ->willReturn(['got' => 'called']);

        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
            $leistungenMock
        );
        $leistungenHelper->getAdditionalData(1234);
    }

    /**
     * @test
     */
    public function getAdditionalDataReturnsArrayFromFindByIdIfCacheIsNotSet(): void
    {
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenMock = $this->createMock(Leistungen::class);
        $leistungenMock
            ->expects(self::atLeastOnce())
            ->method('findById')
            ->with($this->equalTo(1234))
            ->willReturnCallback(function () use ($cache, $leistungenMock) {
                $leistungenListener = new LeistungenListener(new LeistungenHelper(
                    $cache,
                    $leistungenMock // Using $this refers to the mock object itself
                ));
                $leistungenListener(new ModifyServiceBwResponseEvent(
                    '/portal/leistungsdetails/1234',
                    []
                ));
                return [];
            });

        $leistungenHelper = new LeistungenHelper(
            $cache,
            $leistungenMock
        );

        self::assertEquals(
            ['hasFormulare' => false, 'hasProzesse' => false],
            $leistungenHelper->getAdditionalData(1234)
        );
    }

    /**
     * @test
     */
    public function getAdditionalDataReturnsEmptyArrayWithFetchIfMissingFalseAndCacheIsNotSet(): void
    {
        $leistungenMock = $this->createMock(Leistungen::class);
        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
            $leistungenMock
        );
        self::assertEquals(
            [],
            $leistungenHelper->getAdditionalData(1234, false)
        );
    }

    /**
     * @test
     */
    public function getAdditionalDataReturnsPreviouslySavedAdditionalDataFromCache(): void
    {
        $leistungenMock = $this->createMock(Leistungen::class);
        $data = ['important' => 'data'];
        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new TransientMemoryBackend('')),
            $leistungenMock
        );
        $leistungenHelper->saveAdditionalData(1234, $data);

        self::assertEquals(
            $data,
            $leistungenHelper->getAdditionalData(1234)
        );
    }
}
