<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller;

use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Zemit\Bootstrap\Config;
use Zemit\Di\Injectable;

/**
 * Trait Behavior
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller
 *
 * @property Manager $eventsManager
 * @property Config $config
 */
trait Behavior
{
    abstract function getDI(): DiInterface;
    abstract function getModelClass(): ?string;
    
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // @todo use eventsManager from service provider instead
        $this->eventsManager->enablePriorities(true);
        
        // @todo see if we can implement receiving an array of responses globally: V2
        // $this->eventsManager->collectResponses(true);
        
        // retrieve events based on the config roles and features
        $permissions = $this->config->get('permissions')->toArray() ?? [];
        $featureList = $permissions['features'] ?? [];
        $roleList = $permissions['roles'] ?? [];
        
        foreach ($roleList as $role => $rolePermission) {
            
            if (isset($rolePermission['features'])) {
                foreach ($rolePermission['features'] as $feature) {
                    $rolePermission = array_merge_recursive($rolePermission, $featureList[$feature] ?? []);
                    // @todo remove duplicates
                }
            }
            
            $behaviorsContext = $rolePermission['behaviors'] ?? [];
            foreach ($behaviorsContext as $className => $behaviors) {
                if (is_int($className) || get_class($this) === $className) {
                    $this->attachBehaviors($behaviors, 'rest');
                }
                if ($this->getModelClass() === $className) {
                    $this->attachBehaviors($behaviors, 'model');
                }
            }
        }
    }
    
    /**
     * @param $behaviors
     * @param string $eventType
     * @return void
     */
    public function attachBehaviors($behaviors, string $eventType = 'rest'): void
    {
        if (!is_array($behaviors)) {
            $behaviors = [$behaviors];
        }
        foreach ($behaviors as $behavior) {
            $event = new $behavior();
            if ($event instanceof Injectable) {
                $event->setDI($this->getDI());
            }
            $this->eventsManager->attach($event->eventType ?? $eventType, $event, $event->priority ?? Manager::DEFAULT_PRIORITY);
        }
    }
}
