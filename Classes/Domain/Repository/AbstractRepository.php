<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Domain\Repository;

/*
* This file is part of the TYPO3 CMS project.
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 2
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*
* The TYPO3 project - inspiring people to share!
*/

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

    /**
     * Allowed languages for service_bw2
     *
     * @var array
     */
    protected $allowedLanguages = [
        'de' => 0,
        'en' => 1,
        'fr' => 2
    ];

    /**
     * inject objectManager
     *
     * @param ObjectManager $objectManager
     */
    public function injectObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * inject serviceBwClient
     *
     * @param ServiceBwClient $serviceBwClient
     */
    public function injectServiceBwClient(ServiceBwClient $serviceBwClient)
    {
        $this->serviceBwClient = $serviceBwClient;
    }

    /**
     * inject translationService
     *
     * @param TranslationService $translationService
     */
    public function injectTranslationService(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
}
