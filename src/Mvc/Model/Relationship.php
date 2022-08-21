<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Model;

use Exception;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Db\Column;
use Phalcon\Messages\Message;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\ManagerInterface;
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Mvc\Model\Relation;
use Phalcon\Mvc\Model\RelationInterface;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\ModelInterface;
use Zemit\Mvc\Model;
use Zemit\Utils\Sprintf;

/**
 * Trait Relationship
 * Allow to automagically save all kind of relationships
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Model
 */
trait Relationship
{
    protected array $_keepMissingRelated = [];
    protected array $_relationshipContext = [];
    
    protected $dirtyRelated;
    
    /**
     * Returns the models manager related to the entity instance
     *
     * @return ManagerInterface
     */
    abstract public function getModelsManager();
    
    /**
     * @return string
     */
    protected function _getRelationshipContext()
    {
        return implode('.', $this->_relationshipContext);
    }
    
    /**
     * @param $context
     */
    protected function _setRelationshipContext($context)
    {
        $this->_relationshipContext = array_filter(is_array($context) ? $context : explode('.', $context));
    }
    
    /**
     * @param array $data
     * @param null $whiteList
     * @param null $dataColumnMap
     *
     * @return ModelInterface
     * @throws Exception
     */
    public function assign(array $data, $whiteList = null, $dataColumnMap = null): ModelInterface
    {
        $this->assignRelated(...func_get_args());
        
        return parent::assign(...func_get_args());
    }
    
    /**
     * Assign related
     *
     * Single
     * [alias => new Alias()] // create new alias
     *
     * Many
     * [alias => [new Alias()]] // create new alias
     * [alias => [1, 2, 3, 4]] // append / merge 1, 2, 3, 4
     * [alias => [false, 1, 2, 4]]; // delete 3
     *
     * @param array $data
     * @param null $whiteList
     * @param null $dataColumnMap
     *
     * @return $this|ModelInterface
     * @throws Exception
     */
    public function assignRelated(array $data, $whiteList = null, $dataColumnMap = null): ModelInterface
    {
        // no data, nothing to do
        if (empty($data)) {
            return $this;
        }
        
        // Get the current model class name
        $modelClass = get_class($this);
        
        /** @var Manager $modelManager */
        $modelManager = $this->getModelsManager();
        
        foreach ($data as $alias => $relationData) {
            
            /** @var \Phalcon\Mvc\Model\Relation $relation */
            $relation = $modelManager->getRelationByAlias($modelClass, $alias);
            
            // @todo add a resursive whiteList check & columnMap support
            if ($relation) {
                
                $type = $relation->getType();
                
                $fields = $relation->getFields();
                $fields = is_array($fields) ? $fields : [$fields];
                
                $referencedFields = $relation->getReferencedFields();
                $referencedFields = is_array($referencedFields) ? $referencedFields : [$referencedFields];
                
                $referencedModel = $relation->getReferencedModel();
                $assign = null;
                
                if (is_int($relationData) || is_string($relationData)) {
                    $relationData = [$referencedFields[0] => $relationData];
                }
                
                if ($relationData instanceof ModelInterface) {
                    if ($relationData instanceof $referencedModel) {
                        $assign = $relationData;
                    }
                    else {
                        throw new Exception('Instance of `' . get_class($relationData) . '` received on model `' . $modelClass . '` in alias `' . $alias . ', expected instance of `' . $referencedModel . '`', 400);
                    }
                }
                
                // array | traversable | resultset
                else if (is_array($relationData) || $relationData instanceof \Traversable) {
                    $assign = [];
                    
                    $getEntityParams = [
                        'alias' => $alias,
                        'fields' => $referencedFields,
                        'modelClass' => $referencedModel,
                        'readFields' => $fields,
                        'type' => $type,
                        'whiteList' => $whiteList,
                        'dataColumnMap'=> $dataColumnMap,
                    ];
                    
                    if (empty($relationData) && !in_array($type, [Relation::HAS_MANY_THROUGH, Relation::HAS_MANY])) {
                        $assign = $this->_getEntityFromData($relationData, $getEntityParams);
                    }
                    else {
                        foreach ($relationData as $traversedKey => $traversedData) {
                            // Array of things
                            if (is_int($traversedKey)) {
                                $entity = null;
                                
                                // Using bool as behaviour to delete missing relationship or keep them
                                // @TODO find a better way... :(
                                // if [alias => [true, ...]
                                switch($traversedData) {
                                    case 'false':
                                        $traversedData = false;
                                        break;
                                    case 'true':
                                        $traversedData = true;
                                        break;
                                }
                                if (is_bool($traversedData)) {
                                    $this->_keepMissingRelated[$alias] = $traversedData;
                                    continue;
                                }
                                
                                // if [alias => [1, 2, 3, ...]]
                                if (is_int($traversedData) || is_string($traversedData)) {
                                    $traversedData = [$referencedFields[0] => $traversedData];
                                }
                                
                                // if [alias => AliasModel]
                                if ($traversedData instanceof ModelInterface) {
                                    if ($traversedData instanceof $referencedModel) {
                                        $entity = $traversedData;
                                    }
                                    else {
                                        throw new Exception('Instance of `' . get_class($traversedData) . '` received on model `' . $modelClass . '` in alias `' . $alias . ', expected instance of `' . $referencedModel . '`', 400);
                                    }
                                }
                                
                                // if [alias => [[id => 1], [id => 2], [id => 3], ....]]
                                else if (is_array($traversedData) || $traversedData instanceof \Traversable) {
                                    $entity = $this->_getEntityFromData((array)$traversedData, $getEntityParams);
                                }
                                
                                if ($entity) {
                                    $assign [] = $entity;
                                }
                            }
                            
                            // if [alias => [id => 1]]
                            else {
                                $assign = $this->_getEntityFromData((array)$relationData, $getEntityParams);
                                break;
                            }
                        }
                    }
                }
                
                // we got something to assign
                if (!empty($assign) || $this->_keepMissingRelated[$alias] === false) {
    
                    $assign = is_array($assign) ? array_values(array_filter($assign)) : $assign;
                    $this->$alias = $assign;
                    
                    // fix to force recursive parent save from children entities within _preSaveRelatedRecords method
                    if ($this->$alias && $this->$alias instanceof ModelInterface) {
                        $this->$alias->setDirtyState(self::DIRTY_STATE_TRANSIENT);
                    }
                    
                    $this->dirtyRelated[mb_strtolower($alias)] = $this->$alias ?? false;
                    
                    if (empty($assign)) {
                        $this->dirtyRelated[mb_strtolower($alias)] = [];
                    }
                }
            } // END RELATION
        } // END DATA LOOP
        
        return $this;
    }
    
    /**
     * Saves related records that must be stored prior to save the master record
     *
     * @todo Remove in v5.0
     * @deprecated Use preSaveRelatedRecords()
     *
     * @param \Phalcon\Mvc\ModelInterface[] related
     */
    protected function _preSaveRelatedRecords(AdapterInterface $connection, $related): bool
    {
        return $this->preSaveRelatedRecords($connection, $related);
    }
    
    /**
     * Saves related records that must be stored prior to save the master record
     * Refactored based on the native cphalcon version so we can support :
     * - combined keys on relationship definition
     * - relationship context within the model messages based on the alias definition
     *
     * @param \Phalcon\Db\Adapter\AdapterInterface $connection
     * @param $related
     *
     * @return bool
     * @throws Exception
     *
     */
    protected function preSaveRelatedRecords(\Phalcon\Db\Adapter\AdapterInterface $connection, $related): bool
    {
        $nesting = false;
        
        /**
         * Start an implicit transaction
         */
        $connection->begin($nesting);
        
        $className = get_class($this);
        
        /** @var ManagerInterface $manager */
        $manager = $this->getModelsManager();
        
        /**
         * @var string $alias alias
         * @var ModelInterface $record
         */
        foreach ($related as $alias => $record) {
            /**
             * Try to get a relation with the same name
             */
            $relation = $manager->getRelationByAlias($className, $alias);
            
            if ($relation) {
                /**
                 * Get the relation type
                 */
                $type = $relation->getType();
                
                /**
                 * Only belongsTo are stored before save the master record
                 */
                if ($type === Relation::BELONGS_TO) {
                    
                    /**
                     * We only support model interface for the belongs-to relation
                     */
                    if (!($record instanceof ModelInterface)) {
                        $connection->rollback($nesting);
                        throw new Exception(
                            'Instance of `' . get_class($record) . '` received on model `' . $className . '` in alias `' . $alias .
                            ', expected instance of `' . ModelInterface::class . '` as part of the belongs-to relation',
                            400
                        );
                    }
                    
                    /**
                     * Get columns and referencedFields as array
                     */
                    $referencedFields = $relation->getReferencedFields();
                    $columns = $relation->getFields();
                    $referencedFields = is_array($referencedFields) ? $referencedFields : [$referencedFields];
                    $columns = is_array($columns) ? $columns : [$columns];
                    
                    /**
                     * Set the relationship context
                     */
                    $record->_setRelationshipContext($this->_getRelationshipContext() . '.' . $alias);
                    
                    /**
                     * If dynamic update is enabled, saving the record must not take any action
                     * Only save if the model is dirty to prevent circular relations causing an infinite loop
                     */
                    if ($record->getDirtyState() !== Model::DIRTY_STATE_PERSISTENT && !$record->save()) {
                        
                        /**
                         * Append messages with context
                         */
                        $this->appendMessagesFromRecord($record, $alias);
                        
                        /**
                         * Rollback the implicit transaction
                         */
                        $connection->rollback($nesting);
                        
                        return false;
                    }
                    
                    /**
                     * Read the attributes from the referenced model and assign
                     * it to the current model
                     */
                    foreach ($referencedFields as $key => $referencedField) {
                        $this->{$columns[$key]} = $record->readAttribute($referencedField);
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
     * Save the related records assigned in the has-one/has-many relations
     *
     * @todo Remove in v5.0
     * @deprecated Use postSaveRelatedRecords()
     *
     * @param \Phalcon\Mvc\ModelInterface[] related
     * @return bool
     */
    protected function _postSaveRelatedRecords(AdapterInterface $connection, $related): bool
    {
        return $this->postSaveRelatedRecords($connection, $related);
    }
    
    /**
     * NOTE: we need this, this behaviour only happens:
     * - in many to many nodes
     * Fix uniqueness on combined keys in node entities, and possibly more...
     * @link https://forum.phalconphp.com/discussion/2190/many-to-many-expected-behaviour
     * @link http://stackoverflow.com/questions/23374858/update-a-records-n-n-relationships
     * @link https://github.com/phalcon/cphalcon/issues/2871
     *
     * @param \Phalcon\Db\Adapter\AdapterInterface $connection
     * @param $related
     *
     * @return array|bool
     */
    protected function postSaveRelatedRecords(\Phalcon\Db\Adapter\AdapterInterface $connection, $related): bool
    {
        $nesting = false;
        
        if ($related) {
            foreach ($related as $lowerCaseAlias => $assign) {
                
                /** @var Manager $modelManager */
                $modelManager = $this->getModelsManager();
                
                /** @var RelationInterface $relation */
                $relation = $modelManager->getRelationByAlias(get_class($this), $lowerCaseAlias);
                
                // only many to many
                if ($relation) {
                    $alias = $relation->getOption('alias');
                    
                    /**
                     * Discard belongsTo relations
                     */
                    if ($relation->getType() === Relation::BELONGS_TO) {
                        continue;
                    }
                    
                    if (!is_array($assign) && !is_object($assign)) {
                        $connection->rollback($nesting);
                        throw new Exception("Only objects/arrays can be stored as part of has-many/has-one/has-one-through/has-many-to-many relations");
                    }
                    
                    /**
                     * Custom logic for many-to-many relationships
                     */
                    if ($relation->getType() === Relation::HAS_MANY_THROUGH) {
//                        $nodeAssign = [];
                        
                        $originFields = $relation->getFields();
                        $originFields = is_array($originFields) ? $originFields : [$originFields];
                        
                        $intermediateModelClass = $relation->getIntermediateModel();
                        $intermediateFields = $relation->getIntermediateFields();
                        $intermediateFields = is_array($intermediateFields) ? $intermediateFields : [$intermediateFields];
                        
                        $intermediateReferencedFields = $relation->getIntermediateReferencedFields();
                        $intermediateReferencedFields = is_array($intermediateReferencedFields) ? $intermediateReferencedFields : [$intermediateReferencedFields];
                        
                        $referencedFields = $relation->getReferencedFields();
                        $referencedFields = is_array($referencedFields) ? $referencedFields : [$referencedFields];
                        
                        /** @var ModelInterface $intermediate */
                        /** @var ModelInterface|string $intermediateModelClass */
                        $intermediate = new $intermediateModelClass();
                        $intermediatePrimaryKeyAttributes = $intermediate->getModelsMetaData()->getPrimaryKeyAttributes($intermediate);
                        $intermediateBindTypes = $intermediate->getModelsMetaData()->getBindTypes($intermediate);
                        
                        // get current model bindings
                        $originBind = [];
                        foreach ($originFields as $originField) {
                            $originBind [] = $this->{'get' . ucfirst($originField)} ?? $this->$originField ?? null;
                        }
                        
                        $nodeIdListToKeep = [];
                        foreach ($assign as $key => $entity) {
                            // get referenced model bindings
                            $referencedBind = [];
                            foreach ($referencedFields as $referencedField) {
                                $referencedBind [] = $entity->{'get' . ucfirst($referencedField)} ?? $entity->$referencedField ?? null;
                            }
                            
                            /** @var ModelInterface $nodeEntity */
                            $nodeEntity = $intermediateModelClass::findFirst([
                                'conditions' => Sprintf::implodeArrayMapSprintf(array_merge($intermediateFields, $intermediateReferencedFields), ' and ', '[' . $intermediateModelClass . '].[%s] = ?%s'),
                                'bind' => [...$originBind, ...$referencedBind],
                                'bindTypes' => array_fill(0, count($intermediateFields) + count($intermediateReferencedFields), Column::BIND_PARAM_STR),
                            ]);
                            
                            if ($nodeEntity) {
                                $buildPrimaryKey = [];
                                foreach ($intermediatePrimaryKeyAttributes as $intermediatePrimaryKey => $intermediatePrimaryKeyAttribute) {
                                    $buildPrimaryKey [] = $nodeEntity->readAttribute($intermediatePrimaryKeyAttribute);
                                }
                                $nodeIdListToKeep [] = implode('.', $buildPrimaryKey);
                                
                                // Restoring node entities if previously soft deleted
                                if (method_exists($nodeEntity, 'restore') && method_exists($nodeEntity, 'isDeleted')) {
                                    if ($nodeEntity->isDeleted() && !$nodeEntity->restore()) {
                                        
                                        /**
                                         * Append messages with context
                                         */
                                        $this->appendMessagesFromRecord($nodeEntity, $lowerCaseAlias);
                                        
                                        /**
                                         * Rollback the implicit transaction
                                         */
                                        $connection->rollback($nesting);
                                        
                                        return false;
                                    }
                                }
                                
                                // save edge record
                                if (!$entity->save()) {
                                    
                                    /**
                                     * Append messages with context
                                     */
                                    $this->appendMessagesFromRecord($entity, $lowerCaseAlias);
                                    
                                    /**
                                     * Rollback the implicit transaction
                                     */
                                    $connection->rollback($nesting);
                                    
                                    return false;
                                }
                                
                                // remove it
                                unset($assign[$key]);
                                unset($related[$lowerCaseAlias][$key]);

//                                // add to assign
//                                $nodeAssign [] = $nodeEntity;
                            }
                        }
                        
                        if (!($this->_keepMissingRelated[$lowerCaseAlias] ?? true)) {
                            // handle if we empty the related
                            if (empty($nodeIdListToKeep)) {
                                $nodeIdListToKeep = [0];
                            }
                            else {
                                $nodeIdListToKeep = array_values(array_filter(array_unique($nodeIdListToKeep)));
                            }
                            
                            $idBindType = count($intermediatePrimaryKeyAttributes) === 1 ? $intermediateBindTypes[$intermediatePrimaryKeyAttributes[0]] : Column::BIND_PARAM_STR;
                            
                            /** @var ModelInterface|string $intermediateModelClass */
                            $nodeEntityToDeleteList = $intermediateModelClass::find([
                                'conditions' => Sprintf::implodeArrayMapSprintf(array_merge($intermediateFields), ' and ', '[' . $intermediateModelClass . '].[%s] = ?%s')
                                    . ' and concat(' . Sprintf::implodeArrayMapSprintf($intermediatePrimaryKeyAttributes, ', \'.\', ', '[' . $intermediateModelClass . '].[%s]') . ') not in ({id:array})',
                                'bind' => [...$originBind, 'id' => $nodeIdListToKeep],
                                'bindTypes' => [...array_fill(0, count($intermediateFields), Column::BIND_PARAM_STR), 'id' => $idBindType],
                            ]);
                            
                            // delete missing related
                            if (!$nodeEntityToDeleteList->delete()) {
                                
                                /**
                                 * Append messages with context
                                 */
                                $this->appendMessagesFromRecord($nodeEntityToDeleteList, $lowerCaseAlias);
                                
                                /**
                                 * Rollback the implicit transaction
                                 */
                                $connection->rollback($nesting);
                                
                                return false;
                            }
                        }
                    }
                    
                    $columns = $relation->getFields();
                    $referencedFields = $relation->getReferencedFields();
                    $columns = is_array($columns) ? $columns : [$columns];
                    $referencedFields = is_array($referencedFields) ? $referencedFields : [$referencedFields];
                    
                    /**
                     * Create an implicit array for has-many/has-one records
                     */
                    $relatedRecords = $assign instanceof ModelInterface ? [$assign] : $assign;
                    
                    foreach ($columns as $column) {
                        if (!property_exists($this, $column)) {
                            $connection->rollback($nesting);
                            throw new Exception("The column '" . $column . "' needs to be present in the model");
                        }
                    }
                    
                    
                    /**
                     * Get the value of the field from the current model
                     * Check if the relation is a has-many-to-many
                     */
                    $isThrough = (bool)$relation->isThrough();
                    
                    /**
                     * Get the rest of intermediate model info
                     */
                    if ($isThrough) {
                        $intermediateModelClass = $relation->getIntermediateModel();
                        $intermediateFields = $relation->getIntermediateFields();
                        $intermediateReferencedFields = $relation->getIntermediateReferencedFields();
                        $intermediateFields = is_array($intermediateFields) ? $intermediateFields : [$intermediateFields];
                        $intermediateReferencedFields = is_array($intermediateReferencedFields) ? $intermediateReferencedFields : [$intermediateReferencedFields];
                    }
                    
                    
                    foreach ($relatedRecords as $recordAfter) {
                        if (!$isThrough) {
                            foreach ($columns as $key => $column) {
                                $recordAfter->writeAttribute($referencedFields[$key], $this->$column);
                            }
                        }
                        
                        /**
                         * Save the record and get messages
                         */
                        if (!$recordAfter->save()) {
                            
                            /**
                             * Append messages with context
                             */
                            $this->appendMessagesFromRecord($recordAfter, $lowerCaseAlias);
                            
                            /**
                             * Rollback the implicit transaction
                             */
                            $connection->rollback($nesting);
                            
                            return false;
                        }
                        
                        if ($isThrough) {
                            
                            /**
                             * Create a new instance of the intermediate model
                             */
                            $intermediateModel = $modelManager->load($intermediateModelClass);
                            
                            /**
                             *  Has-one-through relations can only use one intermediate model.
                             *  If it already exist, it can be updated with the new referenced key.
                             */
                            if ($relation->getType() === Relation::HAS_ONE_THROUGH) {
                                $bind = [];
                                foreach ($columns as $column) {
                                    $bind[] = $this->$column;
                                }
                                
                                $existingIntermediateModel = $intermediateModelClass::findFirst([
                                    'conditions' => Sprintf::implodeArrayMapSprintf($intermediateFields, ' and ', '[' . $intermediateModelClass . '].[%s] = ?%s'),
                                    'bind' => $bind,
                                    'bindTypes' => array_fill(0, count($bind), Column::BIND_PARAM_STR),
                                ]);
                                
                                if ($existingIntermediateModel) {
                                    $intermediateModel = $existingIntermediateModel;
                                }
                            }
                            
                            foreach ($columns as $key => $column) {
                                /**
                                 * Write value in the intermediate model
                                 */
                                $intermediateModel->writeAttribute($intermediateFields[$key], $this->$column);
                                
                                /**
                                 * Get the value from the referenced model
                                 */
                                $intermediateValue = $recordAfter->readAttribute($referencedFields[$key]);
                                
                                /**
                                 * Write the intermediate value in the intermediate model
                                 */
                                $intermediateModel->writeAttribute($intermediateReferencedFields[$key], $intermediateValue);
                            }
                            
                            
                            /**
                             * Save the record and get messages
                             */
                            if (!$intermediateModel->save()) {
                                
                                /**
                                 * Append messages with context
                                 */
                                $this->appendMessagesFromRecord($intermediateModel, $lowerCaseAlias);
                                
                                /**
                                 * Rollback the implicit transaction
                                 */
                                $connection->rollback($nesting);
                                
                                return false;
                            }
                        }
                    }
                }
                else {
                    if (is_array($assign)) {
                        $connection->rollback($nesting);
                        throw new Exception("There are no defined relations for the model '" . get_class($this) . "' using alias '" . $lowerCaseAlias . "'");
                    }
                }
            }
        }
        
        /**
         * Commit the implicit transaction
         */
        $connection->commit($nesting);
        
        return true;

//        // no commit here cuz parent::_postSaveRelatedRecords will fire it
//        return [true, $connection, array_filter($related ?? [])];
    }
    
    /**
     * Get an entity from data
     *
     * @param array $data
     * @param array $configuration
     *
     * @param string $alias deprecated
     * @param array $fields deprecated
     * @param string $modelClass deprecated
     * @param array|null $readFields deprecated
     * @param int|null $type deprecated
     * @param array|null $whiteList deprecated
     * @param array|null $dataColumnMap deprecated
     *
     * @return ModelInterface
     * @todo unit test for this
     *
     * @return ModelInterface
     */
    public function _getEntityFromData(array $data, array $configuration = []): ModelInterface
    {
        $alias = $configuration['alias'] ?? null;
        $fields = $configuration['fields'] ?? null;
        $modelClass = $configuration['modelClass'] ?? null;
        $readFields = $configuration['readFields'] ?? null;
        $type = $configuration['type'] ?? null;
        $whiteList = $configuration['whiteList'] ?? null;
        
        $dataColumnMap = $configuration['dataColumnMap'] ?? null;
        
        $entity = false;
        
        if ($type === Relation::HAS_ONE || $type === Relation::BELONGS_TO) {
            
            // Set value to compare
            if (!empty($readFields)) {
                foreach ($readFields as $key => $field) {
                    if (empty($data[$fields[$key]])) { // @todo maybe remove this if
                        $value = $this->readAttribute($field);
                        if (!empty($value)) { // @todo maybe remove this if
                            $data [$fields[$key]] = $value;
                        }
                    }
                }
            }
            
        }
        
        // array_keys_exists (if $referencedFields keys exists)
        $dataKeys = array_intersect_key($data, array_flip($fields));
        
        // all keys were found
        if (count($dataKeys) === count($fields)) {
            
            if ($type === Relation::HAS_MANY) {
    
                /** @var MetaData $modelMetaData */
                $modelsMetaData = $this->getModelsMetaData();
                $primaryKeys = $modelsMetaData->getPrimaryKeyAttributes($this);
                
                // Force primary keys for single to many
                foreach ($primaryKeys as $primaryKey) {
                    if (!in_array($primaryKey, $fields, true)) {
                        $dataKeys [$primaryKey] = $data[$primaryKey] ?? null;
                        $fields []= $primaryKey;
                    }
                }
            }
            
            /** @var ModelInterface $entity */
            /** @var ModelInterface|string $modelClass */
            $entity = $modelClass::findFirst([
                'conditions' => Sprintf::implodeArrayMapSprintf($fields, ' and ', '[' . $modelClass . '].[%s] = ?%s'),
                'bind' => array_values($dataKeys),
                'bindTypes' => array_fill(0, count($dataKeys), Column::BIND_PARAM_STR),
            ]);
        }
        
        if (!$entity) {
            /** @var ModelInterface $entity */
            $entity = new $modelClass();
        }
        
        // assign new values
        if ($entity) {
            
            // can be null to bypass, empty array for nothing or filled array
            $assignWhiteList = isset($whiteList[$modelClass]) || isset($whiteList[$alias]);
            $assignColumnMap = isset($dataColumnMap[$modelClass]) || isset($dataColumnMap[$alias]);
            $assignWhiteList = $assignWhiteList? array_merge_recursive($whiteList[$modelClass] ?? [], $whiteList[$alias] ?? []) : null;
            $assignColumnMap = $assignColumnMap? array_merge_recursive($dataColumnMap[$modelClass] ?? [], $dataColumnMap[$alias] ?? []) : null;
            
            $entity->assign($data, $assignWhiteList, $assignColumnMap);
//            $entity->setDirtyState(self::DIRTY_STATE_TRANSIENT);
        }
        
        
        return $entity;
    }
    
    /**
     * Append a message to this model from another record,
     * also prepend a context to the previous context
     *
     * @param ResultsetInterface|ModelInterface $record
     * @param string|null $context
     */
    public function appendMessagesFromRecord($record, string $context = null)
    {
        /**
         * Get the validation messages generated by the
         * referenced model
         */
        foreach ($record->getMessages() as $message) {
            
            /**
             * Set the related model
             */
            $message->setMetaData([
//                'model' => $record,
                'context' => $this->rebuildMessageContext($message, $context),
            ]);
            
            /**
             * Appends the messages to the current model
             */
            $this->appendMessage($message);
        }
    }
    
    /**
     * Rebuilding context for meta data
     *
     * @param Message $message
     * @param string $context
     *
     * @return string
     */
    public function rebuildMessageContext(Message $message, string $context)
    {
        $metaData = $message->getMetaData();
        $previousContext = $metaData['context'] ?? null;
        
        return $context . (empty($previousContext) ? null : '.' . $previousContext);
    }
}
