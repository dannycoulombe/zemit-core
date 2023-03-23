<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc;

use Phalcon\Config\ConfigInterface;
use Phalcon\Di;
use Zemit\Mvc\Router\ModuleRoute;

/**
 * {@inheritDoc}
 */
class Router extends \Phalcon\Mvc\Router
{
    public ConfigInterface $config;
    
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }
    
    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }
    
    /**
     * Router constructor.
     */
    public function __construct(bool $defaultRoutes = true, ?ConfigInterface $config = null)
    {
        parent::__construct(false);
        
        // set the config
        $this->setConfig($config ?? Di::getDefault()->get('config'));
        
        // Set default routes
        if ($defaultRoutes) {
            $this->defaultRoutes();
        }
    }
    
    /**
     * Default routes
     * - Default namespace
     * - Default controller
     * - Default action
     * - Default notFound
     */
    public function defaultRoutes(): void
    {
        $this->removeExtraSlashes(true);
        
        $routerConfig = $this->getConfig()->get('router')->toArray();
        $localeConfig = $this->getConfig()->get('locale')->toArray();
        
        $this->setDefaults($routerConfig['defaults'] ?? $this->getDefaults());
        $this->notFound($routerConfig['notFound'] ?? $this->notFoundPaths);
        $this->mount(new ModuleRoute($this->getDefaults(), $localeConfig['allowed'] ?? [], true));
    }
    
    /**
     * @param array|null $hostnames
     * @param array|null $defaults
     * @return void
     */
    public function hostnamesRoutes(array $hostnames = null, array $defaults = null): void
    {
        $routerConfig = $this->getConfig()->get('router')->toArray();
        $hostnames ??= $routerConfig['hostnames'] ?? [];
        $defaults ??= $this->getDefaults();
        
        foreach ($hostnames as $hostname => $hostnameRoute) {
            if (!isset($hostnameRoute['module']) || !is_string($hostnameRoute['module'])) {
                throw new \InvalidArgumentException('Router hostname config parameter "module" must be a string under "' . $hostname . '"');
            }
            $localeConfig = $this->getConfig()->get('locale')->toArray();
            $this->mount((new ModuleRoute(array_merge($defaults, $hostnameRoute), $localeConfig['allowed'] ?? [], true))->setHostname($hostname));
        }
    }
    
    /**
     * Defines our frontend routes
     * /controller/action/params
     */
    public function modulesRoutes(\Phalcon\Mvc\Application $application, array $defaults = null): void
    {
        $defaults ??= $this->getDefaults();
        foreach ($application->getModules() as $key => $module) {
            if (!isset($module['className'])) {
                throw new \InvalidArgumentException('Module parameter "className" must be a string under "' . $key . '"');
            }
            $localeConfig = $this->getConfig()->get('locale')->toArray();
            $namespace = rtrim($module['className'], 'Module') . 'Controllers';
            $moduleDefaults = ['namespace' => $namespace, 'module' => $key];
            $this->mount(new ModuleRoute(array_merge($defaults, $moduleDefaults), $localeConfig['allowed'] ?? [], true));
        }
    }
    
    public function toArray(): array
    {
        $matchedRoute = $this->getMatchedRoute();
        return [
            'namespace' => $this->getNamespaceName(),
            'module' => $this->getModuleName(),
            'controller' => $this->getControllerName(),
            'action' => $this->getActionName(),
            'params' => $this->getParams(),
            'defaults' => $this->getDefaults(),
            'matches' => $this->getMatches(),
            'matched' => $matchedRoute ? [
                'id' => $matchedRoute->getRouteId(),
                'name' => $matchedRoute->getName(),
                'hostname' => $matchedRoute->getHostname(),
                'paths' => $matchedRoute->getPaths(),
                'pattern' => $matchedRoute->getPattern(),
                'httpMethod' => $matchedRoute->getHttpMethods(),
                'reversedPaths' => $matchedRoute->getReversedPaths(),
            ] : null,
        ];
    }
}
