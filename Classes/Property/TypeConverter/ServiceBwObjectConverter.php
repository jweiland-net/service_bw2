<?php
namespace JWeiland\ServiceBw2\Property\TypeConverter;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Session;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Property\Exception\InvalidPropertyMappingConfigurationException;
use TYPO3\CMS\Extbase\Property\Exception\InvalidSourceException;
use TYPO3\CMS\Extbase\Property\Exception\InvalidTargetException;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\ObjectConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * This converter transforms arrays or strings to persistent objects. It does the following:
 *
 * - If the input is string, it is assumed to be a UID. Then, the object is fetched from persistence.
 * - If the input is array, we check if it has an identity property.
 *
 * - If the input has an identity property and NO additional properties, we fetch the object from persistence.
 * - If the input has an identity property AND additional properties, we fetch the object from persistence,
 *   and set the sub-properties. We only do this if the configuration option "CONFIGURATION_MODIFICATION_ALLOWED" is TRUE.
 * - If the input has NO identity property, but additional properties, we create a new object and return it.
 *   However, we only do this if the configuration option "CONFIGURATION_CREATION_ALLOWED" is TRUE.
 *
 * @api
 */
class ServiceBwObjectConverter extends ObjectConverter
{
    /**
     * @var int
     */
    const CONFIGURATION_MODIFICATION_ALLOWED = 1;

    /**
     * @var int
     */
    const CONFIGURATION_CREATION_ALLOWED = 2;

    /**
     * @var array
     */
    protected $sourceTypes = ['array'];

    /**
     * @var string
     */
    protected $targetType = 'object';

    /**
     * @var int
     */
    protected $priority = 70;

    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Session
     */
    protected $persistenceSession;

    /**
     * @param PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * inject persistenceSession
     *
     * @param Session $persistenceSession
     *
     * @return void
     */
    public function injectPersistenceSession(Session $persistenceSession)
    {
        $this->persistenceSession = $persistenceSession;
    }

    /**
     * We can only convert if the $targetType is either tagged with entity or value object.
     *
     * @param mixed $source
     * @param string $targetType
     *
     * @return bool
     */
    public function canConvertFrom($source, $targetType)
    {
        return is_subclass_of($targetType, AbstractDomainObject::class);
    }

    /**
     * All properties in the source array except __identity are sub-properties.
     *
     * @param mixed $source
     *
     * @return array
     */
    public function getSourceChildPropertiesToBeConverted($source)
    {
        if (isset($source['__identity'])) {
            unset($source['__identity']);
        }
        return parent::getSourceChildPropertiesToBeConverted($source);
    }

    /**
     * The type of a property is determined by the reflection service.
     *
     * @param string $targetType
     * @param string $propertyName
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return string
     *
     * @throws InvalidTargetException
     */
    public function getTypeOfChildProperty($targetType, $propertyName, PropertyMappingConfigurationInterface $configuration)
    {
        $configuredTargetType = $configuration->getConfigurationFor($propertyName)->getConfigurationValue(PersistentObjectConverter::class, self::CONFIGURATION_TARGET_TYPE);
        if ($configuredTargetType !== null) {
            return $configuredTargetType;
        }

        $specificTargetType = $this->objectContainer->getImplementationClassName($targetType);
        $schema = $this->reflectionService->getClassSchema($specificTargetType);
        if (!$schema->hasProperty($propertyName)) {
            throw new InvalidTargetException('Property "' . $propertyName . '" was not found in target object of type "' . $specificTargetType . '".', 1297978366);
        }
        $propertyInformation = $schema->getProperty($propertyName);
        return $propertyInformation['type'] . ($propertyInformation['elementType'] !== null ? '<' . $propertyInformation['elementType'] . '>' : '');
    }

    /**
     * Convert an object from $source to an entity or a value object.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
     *
     * @return object the target type
     *
     * @throws \InvalidArgumentException
     * @throws InvalidTargetException
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        if (is_array($source)) {
            $object = $this->handleArrayData($source, $targetType, $convertedChildProperties, $configuration);
        } else {
            throw new \InvalidArgumentException('Only integers, strings and arrays are accepted.', 1305630314);
        }
        if (isset($source['id'])) {
            $this->persistenceSession->registerObject($object, $source['id'] . '_' . $source['_languageUid']);
        }
        foreach ($convertedChildProperties as $propertyName => $propertyValue) {
            // call setter method
            $result = ObjectAccess::setProperty($object, $propertyName, $propertyValue);
            if ($result === false && $object instanceof AbstractDomainObject) {
                // try to set value directly
                // In most cases these properties will start with "_" (internal)
                $result = $object->_setProperty($propertyName, $propertyValue);
            }
            if ($result === false) {
                $exceptionMessage = sprintf(
                    'Property "%s" having a value of type "%s" could not be set in target object of type "%s". Make sure that the property is accessible properly, for example via an appropriate setter method.',
                    $propertyName,
                    (is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue)),
                    $targetType
                );
                throw new InvalidTargetException($exceptionMessage, 1297935345);
            }
        }

        return $object;
    }

    /**
     * Handle the case if $source is an array.
     *
     * @param array $source
     * @param string $targetType
     * @param array &$convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return object
     *
     * @throws InvalidPropertyMappingConfigurationException
     */
    protected function handleArrayData(array $source, $targetType, array &$convertedChildProperties, PropertyMappingConfigurationInterface $configuration = null)
    {
        $object = null;
        if (isset($source['id'])) {
            $object = $this->fetchObjectFromPersistence($source['id'], $source['_languageUid'], $targetType);

            if (
                $object instanceof $targetType &&
                count($source) > 1 &&
                ($configuration === null || $configuration->getConfigurationValue(ServiceBwObjectConverter::class, self::CONFIGURATION_MODIFICATION_ALLOWED) !== true)
            ) {
                throw new InvalidPropertyMappingConfigurationException('Modification of persistent objects not allowed. To enable this, you need to set the PropertyMappingConfiguration Value "CONFIGURATION_MODIFICATION_ALLOWED" to TRUE.', 1297932028);
            }
        }
        if ($object === null) {
            if ($configuration === null || $configuration->getConfigurationValue(ServiceBwObjectConverter::class, self::CONFIGURATION_CREATION_ALLOWED) !== true) {
                throw new InvalidPropertyMappingConfigurationException(
                    'Creation of objects not allowed. To enable this, you need to set the PropertyMappingConfiguration Value "CONFIGURATION_CREATION_ALLOWED" to TRUE',
                    1476044961
                );
            }
            $object = $this->buildObject($convertedChildProperties, $targetType);
        }
        return $object;
    }

    /**
     * Fetch an object from persistence layer.
     *
     * @param string|int $identity
     * @param string|int $sysLanguageUid
     * @param string $targetType
     *
     * @return object|null
     *
     * @throws TargetNotFoundException
     * @throws InvalidSourceException
     */
    protected function fetchObjectFromPersistence($identity, $sysLanguageUid, $targetType)
    {
        $identifier = $identity . '_' . (int)$sysLanguageUid;
        if (ctype_digit((string)$identity)) {
            if ($this->persistenceSession->hasIdentifier($identifier, $targetType)) {
                $object = $this->persistenceSession->getObjectByIdentifier($identifier, $targetType);
            } else {
                // we need gr_list for FrontendContainer::FrontendGroupRestriction
                $GLOBALS['TSFE'] = new \stdClass();
                $GLOBALS['TSFE']->gr_list = '';

                $query = $this->persistenceManager->createQueryForType($targetType);
                $query->getQuerySettings()->setRespectStoragePage(false);
                $query->getQuerySettings()->setRespectSysLanguage(true);
                $query->getQuerySettings()->setLanguageUid($sysLanguageUid);
                $query->getQuerySettings()->setLanguageMode('strict'); // do not overlay. That's our job
                $object = $query->matching($query->equals('id', $identity))->execute()->getFirst();

                unset($GLOBALS['TSFE']);
            }
        } else {
            throw new InvalidSourceException('The identity property "' . $identity . '" is no UID.', 1297931020);
        }

        return $object;
    }
}
