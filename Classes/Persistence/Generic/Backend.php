<?php
namespace JWeiland\ServiceBw2\Persistence\Generic;

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

/**
 * A persistence backend. This backend maps objects to the relational model of the storage backend.
 * It persists all added, removed and changed objects.
 *
 * SF: Implement patch from here: https://review.typo3.org/#/c/54376/4
 */
class Backend extends \TYPO3\CMS\Extbase\Persistence\Generic\Backend
{
    /**
     * Inserts mm-relation into a relation table
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object The related object
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $parentObject The parent object
     * @param string $propertyName The name of the parent object's property where the related objects are stored in
     * @param int $sortingPosition Defaults to NULL
     *
     * @return int The uid of the inserted row
     */
    protected function insertRelationInRelationtable(\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object, \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $parentObject, $propertyName, $sortingPosition = null)
    {
        $dataMap = $this->dataMapper->getDataMap(get_class($parentObject));
        $columnMap = $dataMap->getColumnMap($propertyName);
        $parentUid = $parentObject->getUid();
        if (!empty($parentObject->_getProperty('_localizedUid'))) {
            $parentUid = $parentObject->_getProperty('_localizedUid');
        }
        $row = [
            $columnMap->getParentKeyFieldName() => (int)$parentUid,
            $columnMap->getChildKeyFieldName() => (int)$object->getUid(),
            $columnMap->getChildSortByFieldName() => !is_null($sortingPosition) ? (int)$sortingPosition : 0
        ];
        $relationTableName = $columnMap->getRelationTableName();
        if ($columnMap->getRelationTablePageIdColumnName() !== null) {
            $row[$columnMap->getRelationTablePageIdColumnName()] = $this->determineStoragePageIdForNewRecord();
        }
        $relationTableMatchFields = $columnMap->getRelationTableMatchFields();
        if (is_array($relationTableMatchFields)) {
            $row = array_merge($relationTableMatchFields, $row);
        }
        $relationTableInsertFields = $columnMap->getRelationTableInsertFields();
        if (is_array($relationTableInsertFields)) {
            $row = array_merge($relationTableInsertFields, $row);
        }
        $res = $this->storageBackend->addRow($relationTableName, $row, true);
        return $res;
    }

    /**
     * Updates a given object in the storage
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object The object to be updated
     * @param array $row Row to be stored
     * @return bool
     */
    protected function updateObject(\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object, array $row)
    {
        $dataMap = $this->dataMapper->getDataMap(get_class($object));
        $this->addCommonFieldsToRow($object, $row);
        $row['uid'] = $object->getUid();
        if ($dataMap->getLanguageIdColumnName() !== null) {
            $row[$dataMap->getLanguageIdColumnName()] = (int)$object->_getProperty('_languageUid');
            if (!empty($object->_getProperty('_localizedUid'))) {
                $row['uid'] = $object->_getProperty('_localizedUid');
            }
        }
        $res = $this->storageBackend->updateRow($dataMap->getTableName(), $row);
        if ($res === true) {
            $this->emitAfterUpdateObjectSignal($object);
        }
        $frameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        if ($frameworkConfiguration['persistence']['updateReferenceIndex'] === '1') {
            $this->referenceIndex->updateRefIndexTable($dataMap->getTableName(), $row['uid']);
        }
        return $res;
    }
}
