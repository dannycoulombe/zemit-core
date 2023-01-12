<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Rest;

use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Http\Response as PhalconResponse;
use Phalcon\Version;
use Zemit\Bootstrap\Config;
use Zemit\Db\Profiler;
use Zemit\Http\Request;
use Zemit\Identity;
use Zemit\Mvc\Dispatcher;
use Zemit\Mvc\Router;
use Zemit\Mvc\View;
use Zemit\Utils;

/**
 * Trait Response
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Rest
 *
 * @property Manager $eventsManager
 * @property Config $config
 * @property View $view
 * @property Identity $identity
 * @property Profiler $profiler
 * @property Request $request
 * @property Dispatcher $dispatcher
 * @property Router $router
 * @property PhalconResponse $response
 */
trait Response
{
    abstract function getDI(): DiInterface;
    
    /**
     * Sending an error as an http response
     *
     * @param null $error
     * @param null $response
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function setRestErrorResponse($code = 400, $status = 'Bad Request', $response = null)
    {
        return $this->setRestResponse($response, $code, $status);
    }
    
    /**
     * Sending rest response as an http response
     *
     * @param array|null $response
     * @param null $status
     * @param null $code
     * @param int $jsonOptions
     * @param int $depth
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function setRestResponse($response = null, $code = null, $status = null, $jsonOptions = 0, $depth = 512)
    {
        $debug = $this->config->app->debug ?? false;
        
        // keep forced status code or set our own
        $responseStatusCode = $this->response->getStatusCode();
        $reasonPhrase = $this->response->getReasonPhrase();
        $status ??= $reasonPhrase ?: 'OK';
        $code ??= (int)$responseStatusCode ?: 200;
        $view = $this->view->getParamsToView();
        $hash = hash('sha512', json_encode($view));
        
        /**
         * Debug section
         * - Versions
         * - Request
         * - Identity
         * - Profiler
         * - Dispatcher
         * - Router
         */
        $request = $debug ? $this->request->toArray() : null;
        $identity = $debug ? $this->identity->getIdentity() : null;
        $profiler = $debug && $this->profiler ? $this->profiler->toArray() : null;
        $dispatcher = $debug ? $this->dispatcher->toArray() : null;
        $router = $debug ? $this->router->toArray() : null;
        
        $api = $debug ? [
            'php' => phpversion(),
            'phalcon' => Version::get(),
            'zemit' => $this->config->core->version,
            'core' => $this->config->core->name,
            'app' => $this->config->app->version,
            'name' => $this->config->app->name,
        ] : [];
        $api['version'] = '0.1';
        
        $this->response->setStatusCode($code, $code . ' ' . $status);
        
        // @todo handle this correctly
        // @todo private vs public cache type
        $cache = $this->getCache();
        if (!empty($cache['lifetime'])) {
            if ($this->response->getStatusCode() === 200) {
                $this->response->setCache($cache['lifetime']);
                $this->response->setEtag($hash);
            }
        }
        else {
            $this->response->setCache(0);
            $this->response->setHeader('Cache-Control', 'no-cache, max-age=0');
        }
        
        return $this->response->setJsonContent(array_merge([
            'api' => $api,
            'timestamp' => date('c'),
            'hash' => $hash,
            'status' => $status,
            'code' => $code,
            'response' => $response,
            'view' => $view,
        ], $debug ? [
            'identity' => $identity,
            'profiler' => $profiler,
            'request' => $request,
            'dispatcher' => $dispatcher,
            'router' => $router,
            'memory' => Utils::getMemoryUsage(),
        ] : []), $jsonOptions, $depth);
    }
    
    /**
     * Handle rest response automagically
     *
     * @param Dispatcher $dispatcher
     */
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        $response = $dispatcher->getReturnedValue();
        
        // Avoid breaking default phalcon behaviour
        if ($response instanceof \Phalcon\Http\Response) {
            return;
        }
        
        // Merge response into view variables
        if (is_array($response)) {
            $this->view->setVars($response, true);
        }
        
        // Return our Rest normalized response
        $dispatcher->setReturnedValue($this->setRestResponse(is_array($response) ? null : $response));
    }
}
