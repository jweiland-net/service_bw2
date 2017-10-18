<?php
namespace JWeiland\ServiceBw2\Persistence\Generic\Mapper;

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

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;

/**
 * A mapper to map database tables configured in $TCA on domain objects.
 *
 * SF: As I get all languages in one response from Service BW I have to
 * create a better identifier for Session object: $row['id'] . '_' . $row['sys_language_uid']
 *
 * SF: Implement patch from here: https://review.typo3.org/#/c/54376/4
 */
class DataMapper extends \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
{
    /**
     * Maps a single row on an object of the given class
     *
     * @param string $className The name of the target class
     * @param array $row A single array with field_name => value pairs
     *
     * @return object An object of the given class
     */
    protected function mapSingleRow($className, array $row)
    {
        $identifier = isset($row['sys_language_uid']) ? $row['id'] . '_' . $row['sys_language_uid'] : $row['id'];
        if ($this->persistenceSession->hasIdentifier($identifier, $className)) {
            $object = $this->persistenceSession->getObjectByIdentifier($identifier, $className);
        } else {
            $object = $this->createEmptyObject($className);
            $this->persistenceSession->registerObject($object, $identifier);
            $this->thawProperties($object, $row);
            $this->emitAfterMappingSingleRow($object);
            $object->_memorizeCleanState();
            $this->persistenceSession->registerReconstitutedEntity($object);
        }
        return $object;
    }

    /**
     * Sets the given properties on the object.
     *
     * @param DomainObjectInterface $object The object to set properties on
     * @param array $row
     */
    protected function thawProperties(DomainObjectInterface $object, array $row)
    {
        $className = get_class($object);
        $classSchema = $this->reflectionService->getClassSchema($className);
        $dataMap = $this->getDataMap($className);
        $object->_setProperty('uid', (int)$row['uid']);
        $object->_setProperty('pid', (int)$row['pid']);
        $object->_setProperty('_localizedUid', (int)$row[$dataMap->getTranslationOriginColumnName()]);
        $object->_setProperty('_versionedUid', (int)$row['uid']);
        if ($dataMap->getLanguageIdColumnName() !== null) {
            $object->_setProperty('_languageUid', (int)$row[$dataMap->getLanguageIdColumnName()]);
            if (isset($row['_LOCALIZED_UID'])) {
                $object->_setProperty('_localizedUid', (int)$row['_LOCALIZED_UID']);
            }
        }
        if (!empty($row['_ORIG_uid']) && !empty($GLOBALS['TCA'][$dataMap->getTableName()]['ctrl']['versioningWS'])) {
            $object->_setProperty('_versionedUid', (int)$row['_ORIG_uid']);
        }
        $properties = $object->_getProperties();
        foreach ($properties as $propertyName => $propertyValue) {
            if (!$dataMap->isPersistableProperty($propertyName)) {
                continue;
            }
            $columnMap = $dataMap->getColumnMap($propertyName);
            $columnName = $columnMap->getColumnName();
            $propertyData = $classSchema->getProperty($propertyName);
            $propertyValue = null;
            if ($row[$columnName] !== null) {
                switch ($propertyData['type']) {
                    case 'integer':
                        $propertyValue = (int)$row[$columnName];
                        break;
                    case 'float':
                        $propertyValue = (double)$row[$columnName];
                        break;
                    case 'boolean':
                        $propertyValue = (bool)$row[$columnName];
                        break;
                    case 'string':
                        $propertyValue = (string)$row[$columnName];
                        break;
                    case 'array':
                        // $propertyValue = $this->mapArray($row[$columnName]); // Not supported, yet!
                        break;
                    case 'SplObjectStorage':
                    case ObjectStorage::class:
                        $propertyValue = $this->mapResultToPropertyValue(
                            $object,
                            $propertyName,
                            $this->fetchRelated($object, $propertyName, $row[$columnName])
                        );
                        break;
                    default:
                        if ($propertyData['type'] === 'DateTime' || in_array('DateTime', class_parents($propertyData['type']))) {
                            $propertyValue = $this->mapDateTime($row[$columnName], $columnMap->getDateTimeStorageFormat(), $propertyData['type']);
                        } elseif (TypeHandlingUtility::isCoreType($propertyData['type'])) {
                            $propertyValue = $this->mapCoreType($propertyData['type'], $row[$columnName]);
                        } else {
                            $propertyValue = $this->mapObjectToClassProperty(
                                $object,
                                $propertyName,
                                $row[$columnName]
                            );
                        }

                }
            }
            if ($propertyValue !== null) {
                $object->_setProperty($propertyName, $propertyValue);
            }
        }
    }
}
