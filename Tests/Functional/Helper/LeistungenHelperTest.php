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
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;

class LeistungenHelperTest extends FunctionalTestCase
{
    use ProphecyTrait;

    protected $testExtensionsToLoad = ['typo3conf/ext/service_bw2'];

    /**
     * @test
     */
    public function saveAdditionalDataWritesDataToCache(): void
    {
        $leistungenProhphecy = $this->prophesize(Leistungen::class);
        $data = ['hello' => 'world', 'time' => time()];
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenHelper = new LeistungenHelper($cache, $leistungenProhphecy->reveal());
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
        $leistungenProhphecy = $this->prophesize(Leistungen::class);
        $leistungenProhphecy->findById(Argument::exact(1234))->willReturn(['got' => 'called'])->shouldBeCalled();
        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
            $leistungenProhphecy->reveal()
        );
        $leistungenHelper->getAdditionalData(1234);
    }

    /**
     * @test
     */
    public function getAdditionalDataReturnsArrayFromFindByIdIfCacheIsNotSet(): void
    {
        $cache = new VariableFrontend(__FUNCTION__, new TransientMemoryBackend(''));
        $leistungenProhphecy = $this->prophesize(Leistungen::class);
        $leistungenProhphecy->findById(Argument::exact(1234))->will(function () use ($cache, $leistungenProhphecy) {
            $leistungenListener = new LeistungenListener(new LeistungenHelper(
                $cache,
                $leistungenProhphecy->reveal()
            ));
            $leistungenListener(new ModifyServiceBwResponseEvent(
                '/portal/leistungsdetails/1234',
                []
            ));
            return [];
        });
        $leistungenHelper = new LeistungenHelper(
            $cache,
            $leistungenProhphecy->reveal()
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
        $leistungenProhphecy = $this->prophesize(Leistungen::class);
        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new NullBackend('')),
            $leistungenProhphecy->reveal()
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
        $leistungenProhphecy = $this->prophesize(Leistungen::class);
        $data = ['important' => 'data'];
        $leistungenHelper = new LeistungenHelper(
            new VariableFrontend(__FUNCTION__, new TransientMemoryBackend('')),
            $leistungenProhphecy->reveal()
        );
        $leistungenHelper->saveAdditionalData(1234, $data);
        self::assertEquals(
            $data,
            $leistungenHelper->getAdditionalData(1234)
        );
    }
}
