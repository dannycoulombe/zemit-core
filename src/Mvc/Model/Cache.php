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

use Phalcon\Mvc\ModelInterface;
use Zemit\Config\ConfigInterface;
use Zemit\Models\Audit;
use Zemit\Models\AuditDetail;
use Zemit\Models\Session;
use Zemit\Mvc\Model\AbstractTrait\AbstractModelsCache;

/**
 * Flush Cache on changes
 *
 * @todo set cache keys
 * @todo improve to delete only necessary keys
 * @todo improve whiteList system
 * @todo precache system
 */
trait Cache
{
    use AbstractModelsCache;
    
    /**
     * Set true to avoid flushing cache for the current instance
     */
    public bool $preventFlushCache = false;
    
    /**
     * Whitelisted classes to not force global cache flush on change
     */
    public array $flushModelsCacheBlackList = [];
    
    /**
     * Initializing Cache
     */
    public function initializeCache(): void
    {
        $config = $this->getDI()->get('config');
        assert($config instanceof ConfigInterface);
        
        $this->flushModelsCacheBlackList [] = $config->getModelClass(Session::class);
        $this->flushModelsCacheBlackList [] = $config->getModelClass(Audit::class);
        $this->flushModelsCacheBlackList [] = $config->getModelClass(AuditDetail::class);
        
        $this->addFlushCacheBehavior($this->flushModelsCacheBlackList);
    }
    
    /**
     * Adding Cache Behavior
     */
    public function addFlushCacheBehavior(?array $flushModelsCacheBlackList = null): void
    {
        $flushModelsCacheBlackList ??= $this->flushModelsCacheBlackList;
        
        // flush cache prevented by current instance
        if ($this->preventFlushCache) {
            return;
        }
        
        // flush cache prevented if current instance class is blacklisted
        if ($this->isInstanceOf($flushModelsCacheBlackList)) {
            return;
        }
        
        $modelsCache = $this->getModelsCache();
        $flushAction = function (ModelInterface $model) use ($modelsCache) {
            // Do not flush cache if nothing has changed
            return ($model->hasSnapshotData() && !($model->hasUpdated() || $model->hasChanged()))
                && $modelsCache->clear();
        };
        
        $actions = ['flush' => $flushAction];
        $this->addBehavior(new Behavior\Action([
            'afterSave' => $actions,
            'afterCreate' => $actions,
            'afterUpdate' => $actions,
            'afterDelete' => $actions,
            'afterRestore' => $actions,
            'afterReorder' => $actions,
        ]));
    }
    
    /**
     * Check whether the current instance is any of the classes
     */
    public function isInstanceOf(array $classes = [], ?ModelInterface $that = null): bool
    {
        $that ??= $this;
        
        // Prevent adding behavior to whiteListed models
        foreach ($classes as $class) {
            if ($that instanceof $class) {
                return true;
            }
        }
        
        return false;
    }
}
