<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * --------------------------------------------------------------
 *
 * New BSD License
 *
 * Copyright (c) 2017-present, Zemit CMS Team
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Zemit nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL ZEMIT FRAMEWORK TEAM BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Zemit\Models\Base;

/**
 * AbstractFile
 * 
 * @package Zemit\Models\Base
 * @autogenerated by Phalcon Developer Tools
 * @date 2022-06-19, 17:39:33
 */
abstract class AbstractFile extends \Zemit\Models\AbstractModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(column="user_id", type="integer", nullable=true)
     */
    protected $userId;

    /**
     *
     * @var string
     * @Column(column="category", type="string", length='partner','speaker','event','other', nullable=false)
     */
    protected $category;

    /**
     *
     * @var string
     * @Column(column="key", type="string", length=50, nullable=true)
     */
    protected $key;

    /**
     *
     * @var string
     * @Column(column="path", type="string", length=120, nullable=true)
     */
    protected $path;

    /**
     *
     * @var string
     * @Column(column="type", type="string", length=100, nullable=true)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(column="type_real", type="string", length=100, nullable=true)
     */
    protected $typeReal;

    /**
     *
     * @var string
     * @Column(column="extension", type="string", length=6, nullable=true)
     */
    protected $extension;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="name_temp", type="string", length=120, nullable=true)
     */
    protected $nameTemp;

    /**
     *
     * @var string
     * @Column(column="size", type="string", length=45, nullable=true)
     */
    protected $size;

    /**
     *
     * @var string
     * @Column(column="error", type="string", nullable=true)
     */
    protected $error;

    /**
     *
     * @var string
     * @Column(column="deleted", type="string", nullable=false)
     */
    protected $deleted;

    /**
     *
     * @var string
     * @Column(column="created_at", type="string", nullable=false)
     */
    protected $createdAt;

    /**
     *
     * @var integer
     * @Column(column="created_by", type="integer", nullable=true)
     */
    protected $createdBy;

    /**
     *
     * @var integer
     * @Column(column="created_as", type="integer", nullable=true)
     */
    protected $createdAs;

    /**
     *
     * @var string
     * @Column(column="updated_at", type="string", nullable=true)
     */
    protected $updatedAt;

    /**
     *
     * @var integer
     * @Column(column="updated_by", type="integer", nullable=true)
     */
    protected $updatedBy;

    /**
     *
     * @var integer
     * @Column(column="updated_as", type="integer", nullable=true)
     */
    protected $updatedAs;

    /**
     *
     * @var string
     * @Column(column="deleted_at", type="string", nullable=true)
     */
    protected $deletedAt;

    /**
     *
     * @var integer
     * @Column(column="deleted_as", type="integer", nullable=true)
     */
    protected $deletedAs;

    /**
     *
     * @var integer
     * @Column(column="deleted_by", type="integer", nullable=true)
     */
    protected $deletedBy;

    /**
     *
     * @var string
     * @Column(column="restored_at", type="string", nullable=true)
     */
    protected $restoredAt;

    /**
     *
     * @var integer
     * @Column(column="restored_by", type="integer", nullable=true)
     */
    protected $restoredBy;

    /**
     *
     * @var integer
     * @Column(column="restored_as", type="integer", nullable=true)
     */
    protected $restoredAs;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Method to set the value of field category
     *
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Method to set the value of field key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Method to set the value of field path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Method to set the value of field type_real
     *
     * @param string $typeReal
     * @return $this
     */
    public function setTypeReal($typeReal)
    {
        $this->typeReal = $typeReal;

        return $this;
    }

    /**
     * Method to set the value of field extension
     *
     * @param string $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field name_temp
     *
     * @param string $nameTemp
     * @return $this
     */
    public function setNameTemp($nameTemp)
    {
        $this->nameTemp = $nameTemp;

        return $this;
    }

    /**
     * Method to set the value of field size
     *
     * @param string $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Method to set the value of field error
     *
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Method to set the value of field deleted
     *
     * @param string $deleted
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Method to set the value of field created_by
     *
     * @param integer $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Method to set the value of field created_as
     *
     * @param integer $createdAs
     * @return $this
     */
    public function setCreatedAs($createdAs)
    {
        $this->createdAs = $createdAs;

        return $this;
    }

    /**
     * Method to set the value of field updated_at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Method to set the value of field updated_by
     *
     * @param integer $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Method to set the value of field updated_as
     *
     * @param integer $updatedAs
     * @return $this
     */
    public function setUpdatedAs($updatedAs)
    {
        $this->updatedAs = $updatedAs;

        return $this;
    }

    /**
     * Method to set the value of field deleted_at
     *
     * @param string $deletedAt
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Method to set the value of field deleted_as
     *
     * @param integer $deletedAs
     * @return $this
     */
    public function setDeletedAs($deletedAs)
    {
        $this->deletedAs = $deletedAs;

        return $this;
    }

    /**
     * Method to set the value of field deleted_by
     *
     * @param integer $deletedBy
     * @return $this
     */
    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Method to set the value of field restored_at
     *
     * @param string $restoredAt
     * @return $this
     */
    public function setRestoredAt($restoredAt)
    {
        $this->restoredAt = $restoredAt;

        return $this;
    }

    /**
     * Method to set the value of field restored_by
     *
     * @param integer $restoredBy
     * @return $this
     */
    public function setRestoredBy($restoredBy)
    {
        $this->restoredBy = $restoredBy;

        return $this;
    }

    /**
     * Method to set the value of field restored_as
     *
     * @param integer $restoredAs
     * @return $this
     */
    public function setRestoredAs($restoredAs)
    {
        $this->restoredAs = $restoredAs;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Returns the value of field category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the value of field key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the value of field path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the value of field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of field typeReal
     *
     * @return string
     */
    public function getTypeReal()
    {
        return $this->typeReal;
    }

    /**
     * Returns the value of field extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field nameTemp
     *
     * @return string
     */
    public function getNameTemp()
    {
        return $this->nameTemp;
    }

    /**
     * Returns the value of field size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns the value of field error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns the value of field deleted
     *
     * @return string
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Returns the value of field createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Returns the value of field createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Returns the value of field createdAs
     *
     * @return integer
     */
    public function getCreatedAs()
    {
        return $this->createdAs;
    }

    /**
     * Returns the value of field updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Returns the value of field updatedBy
     *
     * @return integer
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Returns the value of field updatedAs
     *
     * @return integer
     */
    public function getUpdatedAs()
    {
        return $this->updatedAs;
    }

    /**
     * Returns the value of field deletedAt
     *
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Returns the value of field deletedAs
     *
     * @return integer
     */
    public function getDeletedAs()
    {
        return $this->deletedAs;
    }

    /**
     * Returns the value of field deletedBy
     *
     * @return integer
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Returns the value of field restoredAt
     *
     * @return string
     */
    public function getRestoredAt()
    {
        return $this->restoredAt;
    }

    /**
     * Returns the value of field restoredBy
     *
     * @return integer
     */
    public function getRestoredBy()
    {
        return $this->restoredBy;
    }

    /**
     * Returns the value of field restoredAs
     *
     * @return integer
     */
    public function getRestoredAs()
    {
        return $this->restoredAs;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		parent::initialize();
        // $this->setSchema("zemit_core");
        $this->setSource("file");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractFile[]|AbstractFile|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractFile|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'user_id' => 'userId',
            'category' => 'category',
            'key' => 'key',
            'path' => 'path',
            'type' => 'type',
            'type_real' => 'typeReal',
            'extension' => 'extension',
            'name' => 'name',
            'name_temp' => 'nameTemp',
            'size' => 'size',
            'error' => 'error',
            'deleted' => 'deleted',
            'created_at' => 'createdAt',
            'created_by' => 'createdBy',
            'created_as' => 'createdAs',
            'updated_at' => 'updatedAt',
            'updated_by' => 'updatedBy',
            'updated_as' => 'updatedAs',
            'deleted_at' => 'deletedAt',
            'deleted_as' => 'deletedAs',
            'deleted_by' => 'deletedBy',
            'restored_at' => 'restoredAt',
            'restored_by' => 'restoredBy',
            'restored_as' => 'restoredAs'
        ];
    }

}
