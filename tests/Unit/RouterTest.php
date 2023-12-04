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

namespace Zemit\Tests\Unit;

use Phalcon\Mvc\Router\RouteInterface;
use Zemit\Mvc\Application;
use Zemit\Router\RouterInterface;

/**
 * Class RouterTest
 */
class RouterTest extends AbstractUnit
{
    public function getRouter(): RouterInterface
    {
        return $this->di->get('router');
    }
    
    public function getApplication(): Application
    {
        return $this->di->get('application');
    }
    
    /**
     * Testing the bootstrap service
     */
    public function testRouter(): void
    {
        $routerToArray = $this->getRouter()->toArray();
        $this->assertIsArray($routerToArray);
        $this->assertIsString($routerToArray['namespace']);
        $this->assertEmpty($routerToArray['namespace']);
        $this->assertIsString($routerToArray['module']);
        $this->assertEmpty($routerToArray['module']);
        $this->assertIsString($routerToArray['controller']);
        $this->assertEmpty($routerToArray['controller']);
        $this->assertIsString($routerToArray['action']);
        $this->assertEmpty($routerToArray['action']);
        $this->assertIsArray($routerToArray['params']);
        $this->assertIsArray($routerToArray['defaults']);
        $this->assertIsArray($routerToArray['matches']);
        $this->assertEmpty($routerToArray['matches']);
        $this->assertNull($routerToArray['matched']);

//        $this->assertIsString($routerToArray['matched']['id']);
//        $this->assertIsString($routerToArray['matched']['name']);
//        $this->assertIsString($routerToArray['matched']['hostname']);
//        $this->assertIsString($routerToArray['matched']['paths']);
//        $this->assertIsString($routerToArray['matched']['pattern']);
//        $this->assertIsString($routerToArray['matched']['httpMethod']);
//        $this->assertIsString($routerToArray['matched']['reversedPaths']);
    }
    
    public function testDefaultRoutes(): void
    {
        $routeNames = [
            'default',
            'default-controller',
            'default-controller-action',
        ];
        
        $application = $this->di->get('application');
        $applicationModules = $application->getModules();
        $modules = array_keys($applicationModules);
        $locales = [
            'locale',
            ...$this->bootstrap->config->locale->allowed->toArray(),
        ];
        
        $routePrefixes = [
            'default',
            ...$locales,
            ...$modules,
        ];
        
        $testRoutes = [];
        
        foreach ($routePrefixes as $routePrefix) {
            foreach ($routeNames as $routeName) {
                $testRoute = str_replace('default-', $routePrefix . '-', $routeName);
                $testRoutes[$testRoute] = ['/' . str_replace('-', '/', str_replace(['default-', 'default'], '', $testRoute))];
            }
            foreach ($modules as $module) {
                foreach ($locales as $locale) {
                    $testRoute = str_replace('default-', $locale . '-' . $module . '-', $routePrefix);
                    $testRoutes[$testRoute] = ['/' . str_replace('-', '/', $testRoute)];
                }
            }
        }
        
        foreach ($testRoutes as $name => $uris) {
            
            // add some testing for -action routes
            if (strpos($name, '-action') !== false) {
                $uris [] = $uris[0] . '/params';
                $uris [] = $uris[0] . '/params/1';
                $uris [] = $uris[0] . '/params/1/2';
            }
            
            // can't match locale route, it should match the locale route itself
            if (strpos($name, 'locale') === 0) {
                $uris = [];
            }
            
            $routeByName = $this->getRouter()->getRouteByName($name);
            
            $this->assertInstanceOf(RouteInterface::class, $routeByName, $name . ' : ' . (is_object($routeByName) ? get_class($routeByName) : $routeByName));
            
            foreach ($uris as $uri) {
                $this->getRouter()->handle($uri);
                $matchedRoute = $this->getRouter()->getMatchedRoute();
                $this->assertInstanceOf(RouteInterface::class, $matchedRoute, get_class($matchedRoute));
                
                $message = $uri . ' : ' . json_encode($matchedRoute->getPaths());
                $this->assertEquals($name, $matchedRoute->getName(), $message);
                
                foreach ($modules as $module) {
                    if (strpos($name, $module) !== false) {
                        $this->assertEquals($module, $this->getRouter()->getModuleName(), $message);
                    }
                }
                if (strpos($name, '-controller') !== false) {
                    $this->assertEquals('controller', $this->getRouter()->getControllerName(), $message);
                }
                if (strpos($name, '-action') !== false) {
                    $this->assertEquals('action', $this->getRouter()->getActionName(), $message);
                }
                $this->assertIsString($this->getRouter()->getNamespaceName(), $message);
                $this->assertIsArray($this->getRouter()->getParams(), $message);
            }
        }
    }
}
