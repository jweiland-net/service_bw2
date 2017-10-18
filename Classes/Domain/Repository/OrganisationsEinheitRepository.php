<?php

namespace JWeiland\ServiceBw2\Domain\Repository;

/*
 * This file is part of the service_bw2 project.
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
use JWeiland\ServiceBw2\Domain\Model\OrganisationsEinheit;
use JWeiland\ServiceBw2\Persistence\Generic\Mapper\DataMapper;
use JWeiland\ServiceBw2\Property\TypeConverter\ServiceBwObjectConverter;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Persistence\Repository;
use JWeiland\ServiceBw2\Request;
use TYPO3\CMS\Extbase\Property\PropertyMapper;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrganisationsEinheitRepository extends Repository
{
    /**
     * @var array
     */
    protected $allowedLanguages = [
        'de' => 0,
        'en' => 1,
        'fr' => 2
    ];

    /**
     * @var ServiceBwClient
     */
    protected $serviceBwClient;

    /**
     * @var FrontendInterface
     */
    protected $requestCache;

    /**
     * inject serviceBwClient
     *
     * @param ServiceBwClient $serviceBwClient
     *
     * @return void
     */
    public function injectServiceBwClient(ServiceBwClient $serviceBwClient)
    {
        $this->serviceBwClient = $serviceBwClient;
    }

    /**
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject()
    {
        /** @var CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $this->requestCache = $cacheManager->getCache('servicebw_request');
    }

    /**
     * Get all organizational units from Service BW
     *
     * @return array
     */
    public function getAll()
    {
        /** @var PropertyMapper $propertyMapper */
        $propertyMapper = $this->objectManager->get(PropertyMapper::class);

        /** @var Request\OrganisationsEinheiten\Roots $request */
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Roots::class);

        $records = [];
        $this->addChildren($records, $this->serviceBwClient->processRequest($request));
        $this->requestCache->flushByTag('children');
        // $this->addAnschriften($records);

        $organisationsEinheiten = [];

        foreach ($records as $language => $translatedRecords) {
            foreach ($translatedRecords as $id => $translatedRecord) {
                try {
                    $organisationsEinheit = $propertyMapper->convert(
                        $translatedRecord,
                        OrganisationsEinheit::class,
                        $this->getPropertyMapperConfiguration()
                    );
                    if ($organisationsEinheit instanceof OrganisationsEinheit) {
                        $organisationsEinheiten[$language][$id] = $organisationsEinheit;
                    }
                } catch (\Exception $e) {
                    // @ToDo: Logging or FlashMessage?
                    continue;
                }
            }
            $this->persistenceManager->clearState();
        }

        return $organisationsEinheiten;
    }

    /**
     * Get PropertyMapper configuration for OrganisationsEinheit
     *
     * @return PropertyMappingConfiguration
     */
    protected function getPropertyMapperConfiguration()
    {
        /** @var ServiceBwObjectConverter $serviceBwObjectConverter */
        $serviceBwObjectConverter = $this->objectManager->get(ServiceBwObjectConverter::class);

        /** @var $configuration PropertyMappingConfiguration */
        $configuration = new PropertyMappingConfiguration();
        $configuration
            ->allowAllProperties()
            ->setTypeConverter($serviceBwObjectConverter)
            ->setTypeConverterOptions(ServiceBwObjectConverter::class, [
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED => true,
                PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED => true
            ]);
        $configuration->forProperty('zugehoerigeBehoerde')
            ->allowAllProperties()
            ->setTypeConverter($serviceBwObjectConverter)
            ->setTypeConverterOptions(ServiceBwObjectConverter::class, [
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED => true,
                PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED => true
            ]);

        return $configuration;
    }

    /**
     * Add children recursive to storage
     *
     * @param array $storage
     * @param array $records
     *
     * @return void
     */
    protected function addChildren(array &$storage = [], $records)
    {
        if (is_array($records)) {
            foreach ($records as $language => $organisationsEinheiten) {
                foreach ($organisationsEinheiten as $organisationsEinheit) {
                    // if record is in storage already, continue
                    if (isset($storage[$language]) && array_key_exists($organisationsEinheit['id'], $storage[$language])) {
                        continue;
                    }

                    $children = $this->getChildren($organisationsEinheit['id']);

                    if (is_array($children) && !empty($children)) {
                        $this->addChildren($storage, $children);
                    } else {
                        $storage[$language][$organisationsEinheit['id']] = $organisationsEinheit;
                    }
                }
            }
        }
    }

    /**
     * Add anschriften to storage
     *
     * @param array $records
     *
     * @return void
     */
    protected function addAnschriften(array &$records = [])
    {
        foreach ($records as $language => $organisationsEinheiten) {
            foreach ($organisationsEinheiten as $id => $organisationsEinheit) {
                $records[$language][$id]['anschrift'] = $this->getAnschriften($id, $language);
            }
        }
    }

    /**
     * Get anschriften for a given Organisations Einheit ID
     *
     * @param int $id
     * @param string $language
     *
     * @return array
     */
    protected function getAnschriften($id, $language = 'de')
    {
        if (!$this->requestCache->has('anschriften_' . (int)$id)) {
            /** @var Request\OrganisationsEinheiten\Anschriften $request */
            $request = $this->objectManager->get(Request\OrganisationsEinheiten\Anschriften::class);
            $request->addParameter('id', $id);
            $this->requestCache->set(
                'anschriften_' . (int)$id,
                $this->serviceBwClient->processRequest($request),
                ['anschriften']
            );
        }
        $anschriften = $this->requestCache->get('anschriften_' . (int)$id);
        if (isset($anschriften[$language])) {
            return $anschriften[$language];
        }
        return [];
    }

    /**
     * Get children records of ID
     *
     * @param int $id
     *
     * @return array
     */
    public function getChildren($id)
    {
        $cacheIdentifier = 'children_' . (int)$id;
        if (!$this->requestCache->has($cacheIdentifier)) {
            /** @var Request\OrganisationsEinheiten\Children $request */
            $request = $this->objectManager->get(Request\OrganisationsEinheiten\Children::class);
            $request->addParameter('id', (int)$id);
            $children = $this->serviceBwClient->processRequest($request);

            $this->requestCache->set($cacheIdentifier, $children, ['children']);
        } else {
            $children = $this->requestCache->get($cacheIdentifier);
        }
        return $children;
    }
}
