<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Service\TranslationService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Repository for use of service_bw2
 */
abstract class AbstractRepository implements SingletonInterface
{
    /**
     * Object manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Service BW client
     *
     * @var ServiceBwClient
     */
    protected $serviceBwClient;

    /**
     * @var TranslationService
     */
    protected $translationService;

    public function injectObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    public function injectServiceBwClient(ServiceBwClient $serviceBwClient): void
    {
        $this->serviceBwClient = $serviceBwClient;
    }

    public function injectTranslationService(TranslationService $translationService): void
    {
        $this->translationService = $translationService;
    }
}
