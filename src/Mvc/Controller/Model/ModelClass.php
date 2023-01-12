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

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Text;
use Phalcon\Mvc\ModelInterface;
use Zemit\Mvc\Dispatcher;

/**
 * Trait ModelClass
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait ModelClass
{
    protected ?string $modelClass;
    
    /**
     * Get the injected DI
     */
    abstract function getDI(): DiInterface;
    
    /**
     * Retrieve the mvc dispatcher from DI
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->getDI()->get('dispatcher');
    }
    
    /**
     * Retrieve the loader from DI
     */
    public function getLoader(): Loader
    {
        return $this->getDI()->get('loader');
    }
    
    /**
     * Get the Model Class
     * - If no modelClass is defined will return the model class from the controller
     */
    public function getModelClass(): ?string
    {
        return $this->modelClass ?? $this->getModelClassFromController();
    }
    
    /**
     * @param string $modelClass
     * @return void
     */
    public function setModelClass(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }
    
    /**
     * @return void
     */
    public function resetModelClass()
    {
        $this->modelClass = null;
    }
    
    /**
     * Try to find the appropriate model from the current controller name
     *
     * @param ?string $controllerName
     * @param ?array $namespaces
     * @param string $needle
     *
     * @return string|null
     */
    public function getModelClassFromController(string $controllerName = null, array $namespaces = null, string $needle = 'Models'): ?string
    {
        $controllerName ??= $this->getDispatcher()->getControllerName() ?? '';
        $namespaces ??= $this->getLoader()->getNamespaces() ?? [];
        
        $model = ucfirst(Text::camelize(Text::uncamelize($controllerName)));
        if (!class_exists($model)) {
            foreach ($namespaces as $namespace => $path) {
                $possibleModel = $namespace . '\\' . $model;
                if (strpos($namespace, $needle) !== false && class_exists($possibleModel)) {
                    $model = $possibleModel;
                }
            }
        }
        
        return class_exists($model) && new $model() instanceof ModelInterface ? $model : null;
    }
    
    /**
     * @todo remove before next major release
     * @deprecated changed to getModelClass()
     */
    public function getModelNameFromController()
    {
        return $this->getModelClassFromController();
    }
    
    /**
     * @todo remove before next major release
     * @deprecated changed to getModelClass()
     */
    public function getModelName()
    {
        return $this->getModelClass();
    }
    
    /**
     * @todo remove before next major release
     * @deprecated changed to getModelClass() instead
     */
    public function getModelClassName()
    {
        return $this->getModelClass();
    }
}
