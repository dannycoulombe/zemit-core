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
 * @property Request $request
 * @property Dispatcher $dispatcher
 */
trait Forward
{
    abstract function getDI(): DiInterface;
    abstract function getParam(string $field);
    
    /**
     * Rest Bootstrap
     */
    public function indexAction($id = null)
    {
        $this->forward($id);
    }
    
    /**
     * Rest bootstrap forwarding
     */
    protected function forward($id = null)
    {
        $id ??= $this->getParam('id');
        
        if ($this->request->isPost() || $this->request->isPut() || $this->request->isPatch()) {
            $this->dispatcher->forward(['action' => 'save']);
        }
        else if ($this->request->isDelete()) {
            $this->dispatcher->forward(['action' => 'delete']);
        }
        else if ($this->request->isGet()) {
            if (is_null($id)) {
                $this->dispatcher->forward(['action' => 'getList']);
            }
            else {
                $this->dispatcher->forward(['action' => 'get']);
            }
        }
    }
}
