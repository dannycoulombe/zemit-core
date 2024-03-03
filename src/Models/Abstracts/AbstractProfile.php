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

namespace Zemit\Models\Abstracts;

/**
 * AbstractProfile
 * 
 * @package Zemit\Models\Abstracts
 * @autogenerated by Phalcon Developer Tools
 * @date 2024-03-03, 15:57:36
 */
abstract class AbstractProfile extends \Zemit\Models\AbstractModel
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $userId;

    /**
     *
     * @var integer
     */
    protected $deleted;

    /**
     *
     * @var string
     */
    protected $createdAt;

    /**
     *
     * @var integer
     */
    protected $createdBy;

    /**
     *
     * @var integer
     */
    protected $createdAs;

    /**
     *
     * @var string
     */
    protected $updatedAt;

    /**
     *
     * @var integer
     */
    protected $updatedBy;

    /**
     *
     * @var integer
     */
    protected $updatedAs;

    /**
     *
     * @var string
     */
    protected $deletedAt;

    /**
     *
     * @var integer
     */
    protected $deletedAs;

    /**
     *
     * @var integer
     */
    protected $deletedBy;

    /**
     *
     * @var string
     */
    protected $restoredAt;

    /**
     *
     * @var integer
     */
    protected $restoredBy;

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
     * Method to set the value of field deleted
     *
     * @param integer $deleted
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
     * Returns the value of field deleted
     *
     * @return integer
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
     * Initialize method for model.
     */
    public function initialize() :void
    {
        parent::initialize();
        // $this->setSchema("zemit_core");
        $this->setSource("profile");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractProfile[]|AbstractProfile|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractProfile|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
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
            'restored_by' => 'restoredBy'
        ];
    }

}
