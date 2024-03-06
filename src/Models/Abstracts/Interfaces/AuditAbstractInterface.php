<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Zemit\Models\Abstracts\Interfaces;

use Phalcon\Db\RawValue;
use Zemit\Mvc\ModelInterface;

interface AuditAbstractInterface extends ModelInterface
{
    /**
     * Returns the value of field id
     * Column: id
     * Attributes: First | Primary | NotNull | Numeric | Unsigned | AutoIncrement | Type(14)
     * @return mixed
     */
    public function getId();
    
    /**
     * Sets the value of field id
     * Column: id 
     * Attributes: First | Primary | NotNull | Numeric | Unsigned | AutoIncrement | Type(14)
     * @param mixed $id
     * @return void
     */
    public function setId($id);
    
    /**
     * Returns the value of field parentId
     * Column: parent_id
     * Attributes: Numeric | Unsigned | Type(14)
     * @return mixed
     */
    public function getParentId();
    
    /**
     * Sets the value of field parentId
     * Column: parent_id 
     * Attributes: Numeric | Unsigned | Type(14)
     * @param mixed $parentId
     * @return void
     */
    public function setParentId($parentId);
    
    /**
     * Returns the value of field model
     * Column: model
     * Attributes: NotNull | Size(255) | Type(2)
     * @return mixed
     */
    public function getModel();
    
    /**
     * Sets the value of field model
     * Column: model 
     * Attributes: NotNull | Size(255) | Type(2)
     * @param mixed $model
     * @return void
     */
    public function setModel($model);
    
    /**
     * Returns the value of field table
     * Column: table
     * Attributes: NotNull | Size(60) | Type(2)
     * @return mixed
     */
    public function getTable();
    
    /**
     * Sets the value of field table
     * Column: table 
     * Attributes: NotNull | Size(60) | Type(2)
     * @param mixed $table
     * @return void
     */
    public function setTable($table);
    
    /**
     * Returns the value of field primary
     * Column: primary
     * Attributes: NotNull | Numeric | Unsigned
     * @return mixed
     */
    public function getPrimary();
    
    /**
     * Sets the value of field primary
     * Column: primary 
     * Attributes: NotNull | Numeric | Unsigned
     * @param mixed $primary
     * @return void
     */
    public function setPrimary($primary);
    
    /**
     * Returns the value of field event
     * Column: event
     * Attributes: NotNull | Size('create','update','delete','restore','other') | Type(18)
     * @return mixed
     */
    public function getEvent();
    
    /**
     * Sets the value of field event
     * Column: event 
     * Attributes: NotNull | Size('create','update','delete','restore','other') | Type(18)
     * @param mixed $event
     * @return void
     */
    public function setEvent($event);
    
    /**
     * Returns the value of field columns
     * Column: columns
     * Attributes: Type(6)
     * @return mixed
     */
    public function getColumns();
    
    /**
     * Sets the value of field columns
     * Column: columns 
     * Attributes: Type(6)
     * @param mixed $columns
     * @return void
     */
    public function setColumns($columns);
    
    /**
     * Returns the value of field before
     * Column: before
     * Attributes: Type(23)
     * @return mixed
     */
    public function getBefore();
    
    /**
     * Sets the value of field before
     * Column: before 
     * Attributes: Type(23)
     * @param mixed $before
     * @return void
     */
    public function setBefore($before);
    
    /**
     * Returns the value of field after
     * Column: after
     * Attributes: Type(23)
     * @return mixed
     */
    public function getAfter();
    
    /**
     * Sets the value of field after
     * Column: after 
     * Attributes: Type(23)
     * @param mixed $after
     * @return void
     */
    public function setAfter($after);
    
    /**
     * Returns the value of field deleted
     * Column: deleted
     * Attributes: NotNull | Numeric | Unsigned | Type(26)
     * @return mixed
     */
    public function getDeleted();
    
    /**
     * Sets the value of field deleted
     * Column: deleted 
     * Attributes: NotNull | Numeric | Unsigned | Type(26)
     * @param mixed $deleted
     * @return void
     */
    public function setDeleted($deleted);
    
    /**
     * Returns the value of field createdAt
     * Column: created_at
     * Attributes: NotNull | Type(4)
     * @return mixed
     */
    public function getCreatedAt();
    
    /**
     * Sets the value of field createdAt
     * Column: created_at 
     * Attributes: NotNull | Type(4)
     * @param mixed $createdAt
     * @return void
     */
    public function setCreatedAt($createdAt);
    
    /**
     * Returns the value of field createdBy
     * Column: created_by
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getCreatedBy();
    
    /**
     * Sets the value of field createdBy
     * Column: created_by 
     * Attributes: Numeric | Unsigned
     * @param mixed $createdBy
     * @return void
     */
    public function setCreatedBy($createdBy);
    
    /**
     * Returns the value of field createdAs
     * Column: created_as
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getCreatedAs();
    
    /**
     * Sets the value of field createdAs
     * Column: created_as 
     * Attributes: Numeric | Unsigned
     * @param mixed $createdAs
     * @return void
     */
    public function setCreatedAs($createdAs);
    
    /**
     * Returns the value of field updatedAt
     * Column: updated_at
     * Attributes: Type(4)
     * @return mixed
     */
    public function getUpdatedAt();
    
    /**
     * Sets the value of field updatedAt
     * Column: updated_at 
     * Attributes: Type(4)
     * @param mixed $updatedAt
     * @return void
     */
    public function setUpdatedAt($updatedAt);
    
    /**
     * Returns the value of field updatedBy
     * Column: updated_by
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getUpdatedBy();
    
    /**
     * Sets the value of field updatedBy
     * Column: updated_by 
     * Attributes: Numeric | Unsigned
     * @param mixed $updatedBy
     * @return void
     */
    public function setUpdatedBy($updatedBy);
    
    /**
     * Returns the value of field updatedAs
     * Column: updated_as
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getUpdatedAs();
    
    /**
     * Sets the value of field updatedAs
     * Column: updated_as 
     * Attributes: Numeric | Unsigned
     * @param mixed $updatedAs
     * @return void
     */
    public function setUpdatedAs($updatedAs);
    
    /**
     * Returns the value of field deletedAt
     * Column: deleted_at
     * Attributes: Type(4)
     * @return mixed
     */
    public function getDeletedAt();
    
    /**
     * Sets the value of field deletedAt
     * Column: deleted_at 
     * Attributes: Type(4)
     * @param mixed $deletedAt
     * @return void
     */
    public function setDeletedAt($deletedAt);
    
    /**
     * Returns the value of field deletedAs
     * Column: deleted_as
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getDeletedAs();
    
    /**
     * Sets the value of field deletedAs
     * Column: deleted_as 
     * Attributes: Numeric | Unsigned
     * @param mixed $deletedAs
     * @return void
     */
    public function setDeletedAs($deletedAs);
    
    /**
     * Returns the value of field deletedBy
     * Column: deleted_by
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getDeletedBy();
    
    /**
     * Sets the value of field deletedBy
     * Column: deleted_by 
     * Attributes: Numeric | Unsigned
     * @param mixed $deletedBy
     * @return void
     */
    public function setDeletedBy($deletedBy);
    
    /**
     * Returns the value of field restoredAt
     * Column: restored_at
     * Attributes: Type(4)
     * @return mixed
     */
    public function getRestoredAt();
    
    /**
     * Sets the value of field restoredAt
     * Column: restored_at 
     * Attributes: Type(4)
     * @param mixed $restoredAt
     * @return void
     */
    public function setRestoredAt($restoredAt);
    
    /**
     * Returns the value of field restoredBy
     * Column: restored_by
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getRestoredBy();
    
    /**
     * Sets the value of field restoredBy
     * Column: restored_by 
     * Attributes: Numeric | Unsigned
     * @param mixed $restoredBy
     * @return void
     */
    public function setRestoredBy($restoredBy);
    
    /**
     * Returns the value of field restoredAs
     * Column: restored_as
     * Attributes: Numeric | Unsigned
     * @return mixed
     */
    public function getRestoredAs();
    
    /**
     * Sets the value of field restoredAs
     * Column: restored_as 
     * Attributes: Numeric | Unsigned
     * @param mixed $restoredAs
     * @return void
     */
    public function setRestoredAs($restoredAs);
}