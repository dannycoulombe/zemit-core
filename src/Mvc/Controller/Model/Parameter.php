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

use Zemit\Http\Request;
use Zemit\Mvc\Dispatcher;

/**
 * Trait Param
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 *
 * @property Dispatcher $dispatcher
 * @property Request $request
 */
trait Parameter
{
    /**
     * @param string $key
     * @param string[]|string|null $filters
     * @param string|null $default
     * @param array|null $params
     *
     * @return mixed
     */
    public function getParam(string $key, $filters = null, string $default = null, array $params = null)
    {
        $params ??= $this->getParams();
        
        return $this->filter->sanitize($params[$key] ?? $this->dispatcher->getParam($key, $filters, $default), $filters);
    }
    
    /**
     * Get parameters from
     * - JsonRawBody, post, put or get
     */
    protected function getParams(array $filters = null): array
    {
        $request = $this->request;
        
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $request->setParameterFilters($filter['name'], $filter['filters'], $filter['scope']);
            }
        }

//        $params = empty($request->getRawBody()) ? [] : $request->getJsonRawBody(true); // @TODO handle this differently
        $params = array_merge_recursive(
            $request->getFilteredQuery(), // $_GET
            $request->getFilteredPut(), // $_PUT
            $request->getFilteredPost(), // $_POST
        );
        
        if (isset($params['_url'])) {
            unset($params['_url']);
        }
        
        return $params;
    }
    
    /**
     * @param $field
     * @param string $sanitizer
     * @param string $glue
     *
     * @return array|string[]
     */
    public function getParamExplodeArrayMapFilter($field, $sanitizer = 'string', $glue = ',')
    {
        $filter = $this->filter;
        $ret = array_filter(array_map(function ($e) use ($filter, $sanitizer) {
            
            // allow to run RAND()
            if (strrpos($e, 'RAND()') === 0) {
                return $e;
            }
            
            return $this->appendModelName(trim($filter->sanitize($e, $sanitizer)));
        }, explode($glue, $this->getParam($field, $sanitizer))));
        
        return empty($ret) ? null : $ret;
    }
}
