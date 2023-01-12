<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Model;

use Phalcon\Messages\Message;
use Phalcon\Mvc\ModelInterface;

/**
 * Trait Save
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait Save
{
//    abstract function getSingle();
//    abstract function getExpose();
//    abstract function getModelClass();
//    abstract function getWhitelist();
//    abstract function getColumnMap();
//    abstract function getWith();
    abstract function getParams(array $filters = null): array;
    abstract function getParam(string $key, $filters = null, string $default = null, array $params = null);
    
    /**
     * Saving model automagically
     *
     * Note:
     * If a newly created entity can't be retrieved using the ->getSingle
     * method after it's creation, the entity will be returned directly
     *
     * @TODO Support Composite Primary Key
     *
     * @param null|int|string $id
     * @param null|\Zemit\Mvc\Model $entity
     * @param null|mixed $post
     * @param null|string $modelName
     * @param null|array $whiteList
     * @param null|array $columnMap
     * @param null|array $with
     *
     * @return array
     */
    protected function save($id = null, $entity = null, $post = null, $modelName = null, $whiteList = null, $columnMap = null, $with = null)
    {
        $single = false;
        $retList = [];
        
        // Get the model name to play with
        $modelName ??= $this->getModelClass();
        $post ??= $this->getParams();
        $whiteList ??= $this->getWhitelist();
        $columnMap ??= $this->getColumnMap();
        $with ??= $this->getWith();
        $id = (int)$id;
        
        // Check if multi-d post
        if (!empty($id) || !isset($post[0]) || !is_array($post[0])) {
            $single = true;
            $post = [$post];
        }
        
        // Save each posts
        foreach ($post as $key => $singlePost) {
            $ret = [];
            
            $singlePostId = (!$single || empty($id)) ? $this->getParam('id', 'int', $this->getParam('int', 'int', null)) : $id;
            if (isset($singlePost['id'])) {
                unset($singlePost['id']);
            }
            
            /** @var \Zemit\Mvc\Model $singlePostEntity */
            $singlePostEntity = (!$single || !isset($entity)) ? $this->getSingle($singlePostId, $modelName) : $entity;
            
            // Create entity if not exists
            if (!$singlePostEntity && empty($singlePostId)) {
                $singlePostEntity = new $modelName();
            }
            
            if (!$singlePostEntity) {
                $ret = [
                    'saved' => false,
                    'messages' => [new Message('Entity id `' . $singlePostId . '` not found.', $modelName, 'NotFound', 404)],
                    'model' => $modelName,
                    'source' => (new $modelName)->getSource(),
                ];
            }
            else {
                // allow custom manipulations
                // @todo move this using events
                $this->beforeAssign($singlePostEntity, $singlePost, $whiteList, $columnMap);
                
                // assign & save
                $singlePostEntity->assign($singlePost, $whiteList, $columnMap);
                $ret = $this->saveEntity($singlePostEntity);
                
                // refetch & expose
                $fetch = $this->getSingle($singlePostEntity->getId(), $modelName, $with);
                $ret[$single ? 'single' : 'list'] = $fetch ? $fetch->expose($this->getExpose()) : false;
            }
            
            $retList [] = $ret;
        }
        
        return $single ? $retList[0] : $retList;
    }
    
    /**
     * Allow overrides to add alter variables before entity assign & save
     * @param ModelInterface $entity
     * @param array $post
     * @param array|null $whiteList
     * @param array|null $columnMap
     * @return void
     */
    protected function beforeAssign(ModelInterface &$entity, Array &$post, ?Array &$whiteList, ?Array &$columnMap): void {
    
    }
    
    /**
     * @param $single
     *
     * @return void
     */
    protected function saveEntity($entity): array
    {
        $ret = [];
        $ret['saved'] = $entity->save();
        $ret['messages'] = $entity->getMessages();
        $ret['model'] = get_class($entity);
        $ret['source'] = $entity->getSource();
        $ret['entity'] = $entity->expose($this->getExpose());
        return $ret;
    }
}
