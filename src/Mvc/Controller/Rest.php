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

use League\Csv\CharsetConverter;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Resultset;
use League\Csv\Writer;
use Phalcon\Version;
use Zemit\Db\Profiler;
use Zemit\Di\Injectable;
use Zemit\Mvc\Controller\Model\Collection;
use Zemit\Mvc\Controller\Rest\Forward;
use Zemit\Mvc\Controller\Rest\Response;
use Zemit\Utils;
use Zemit\Utils\Slug;

/**
 * Class Rest
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 *
 * @property Profiler $profiler
 * @package Zemit\Mvc\Controller
 */
class Rest extends \Zemit\Mvc\Controller
{
    use Model;
    use Behavior;
    use Response;
    use Forward;
    
    public function initialize() {
        $this->whitelist = new Collection([]);
    }
    
    /**
     * Retrieving a single record
     * Alias of method getAction()
     * @param null $id
     *
     * @return bool|\Phalcon\Http\ResponseInterface
     * @deprecated Should use getAction() method instead
     *
     */
    public function getSingleAction($id = null)
    {
        return $this->getAction($id);
    }
    
    /**
     * Retrieving a single record
     *
     * @param null $id
     *
     * @return bool|\Phalcon\Http\ResponseInterface
     */
    public function getAction($id = null)
    {
        $modelName = $this->getModelClass();
        $single = $this->getSingle($id, $modelName, null);
        
        $this->view->single = $single ? $single->expose($this->getExpose()) : false;
        $this->view->model = $modelName;
        $this->view->source = $single ? $single->getSource() : false;
        
        if (!$single) {
            $this->response->setStatusCode(404, 'Not Found');
            
            return false;
        }
        
        return $this->setRestResponse();
    }
    
    /**
     * Retrieving a record list
     * Alias of method getListAction()
     * @return \Phalcon\Http\ResponseInterface
     * @deprecated Should use getListAction() method instead
     *
     */
    public function getAllAction()
    {
        return $this->getListAction();
    }
    
    /**
     * Retrieving a record list
     *
     * @return \Phalcon\Http\ResponseInterface
     * @throws \Exception
     */
    public function getListAction()
    {
        $model = $this->getModelClass();
        
        /** @var Resultset $with */
        $find = $this->getFind();
        $with = $model::with($this->getListWith() ?: [], $find ?: []);
        
        /**
         * Expose the list
         * @var int $key
         * @var \Zemit\Mvc\Model $item
         */
        $list = [];
        foreach ($with as $key => $item) {
            $list[$key] = $item->expose($this->getListExpose());
        }
        
        $list = is_array($list) ? array_values(array_filter($list)) : $list;
        $this->view->list = $list;
        $this->view->listCount = count($list);
        $this->view->totalCount = $model::find($this->getFindCount($find));
        $this->view->totalCount = is_int($this->view->totalCount) ? $this->view->totalCount : count($this->view->totalCount); // @todo fix count to work with rollup when joins
        $this->view->limit = $find['limit'] ?? false;
        $this->view->offset = $find['offset'] ?? false;
        $this->view->find = ($this->config->app->debug || $this->config->debug->enable) ? $find : false;
        
        return $this->setRestResponse();
    }
    
    /**
     * Exporting a record list into a CSV stream
     *
     * @return \Phalcon\Http\ResponseInterface
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Zemit\Exception
     */
    public function exportAction()
    {
        $model = $this->getModelClass();
        $params = $this->view->getParamsToView();
        $contentType = $this->getContentType();
        $fileName = ucfirst(Slug::generate(basename(str_replace('\\', '/', $this->getModelClass())))) . ' List (' . date('Y-m-d') . ')';
        
        /** @var Resultset $with */
        $find = $this->getFind();
        $with = $model::with($this->getListWith() ?: [], $find ?: []);
        
        /**
         * Expose the list
         * @var int $key
         * @var \Zemit\Mvc\Model $item
         */
        $list = [];
        foreach ($with as $key => $item) {
            $list[$key] = $item->expose($this->getExportExpose());
        }
        
        $list = is_array($list) ? array_values(array_filter($list)) : $list;
        $this->flatternArrayForCsv($list);
        $this->formatColumnText($list);
        
        if ($contentType === 'json') {
//            $this->response->setJsonContent($list);
            $this->response->setContent(json_encode($list, JSON_PRETTY_PRINT, 2048));
            $this->response->setContentType('application/json');
            $this->response->setHeader('Content-disposition', 'attachment; filename="' . addslashes($fileName) . '.json"');
            return $this->response->send();
        }
        
        // CSV
        if ($contentType === 'csv') {
            
            // Get CSV custom request parameters
            $mode = $params['mode'] ?? null;
            $delimiter = $params['delimiter'] ?? null;
            $newline = $params['newline'] ?? null;
            $escape = $params['escape'] ?? null;
            $outputBOM = $params['outputBOM'] ?? null;
            $skipIncludeBOM = $params['skipIncludeBOM'] ?? null;

//            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv = Writer::createFromStream(fopen('php://memory', 'r+'));
            
            // CSV - MS Excel on MacOS
            if ($mode === 'mac') {
                $csv->setOutputBOM(Writer::BOM_UTF16_LE); // utf-16
                $csv->setDelimiter("\t"); // tabs separated
                $csv->setNewline("\r\n"); // new lines
                CharsetConverter::addTo($csv, 'UTF-8', 'UTF-16');
            }
            
            // CSV - MS Excel on Windows
            else {
                $csv->setOutputBOM(Writer::BOM_UTF8); // utf-8
                $csv->setDelimiter(','); // comma separated
                $csv->setNewline("\n"); // new line windows
                CharsetConverter::addTo($csv, 'UTF-8', 'UTF-8');
            }
            
            // Apply forced params from request
            if (isset($outputBOM)) {
                $csv->setOutputBOM($outputBOM);
            }
            if (isset($delimiter)) {
                $csv->setDelimiter($delimiter);
            }
            if (isset($newline)) {
                $csv->setNewline($newline);
            }
            if (isset($escape)) {
                $csv->setEscape($escape);
            }
            if ($skipIncludeBOM) {
                $csv->skipInputBOM();
            }
            else {
                $csv->includeInputBOM();
            }
            
            // CSV
            $csv->insertOne(array_keys($list[0]));
            $csv->insertAll($list);
            $csv->output($fileName . '.csv');
            die;
        }
        
        // XLSX
        if ($contentType === 'xlsx') {
            $xlsxArray = [];
            foreach ($list as $array) {
                if (empty($xlsxArray)) {
                    $xlsxArray [] = array_keys($array);
                }
                $xlsxArray [] = array_values($array);
            }
            $xlsx = \SimpleXLSXGen::fromArray($xlsxArray);
            $xlsx->downloadAs($fileName . '.xlsx');
            die;
        }
        
        // Something went wrong
        throw new \Exception('Failed to export `' . $this->getModelClass() . '` using content-type `' . $contentType . '`', 400);
    }
    
    /**
     * Count a record list
     * @TODO add total count / deleted count / active count
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function countAction()
    {
        $model = $this->getModelClass();
        
        /** @var \Zemit\Mvc\Model $entity */
        $entity = new $model();
        
        $this->view->totalCount = $model::count($this->getFindCount($this->getFind()));
        $this->view->totalCount = is_int($this->view->totalCount) ? $this->view->totalCount : count($this->view->totalCount);
        $this->view->model = get_class($entity);
        $this->view->source = $entity->getSource();
        
        return $this->setRestResponse();
    }
    
    /**
     * Prepare a new model for the frontend
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function newAction()
    {
        $model = $this->getModelClass();
        
        /** @var \Zemit\Mvc\Model $entity */
        $entity = new $model();
        $entity->assign($this->getParams(), $this->getWhiteList(), $this->getColumnMap());
        
        $this->view->model = get_class($entity);
        $this->view->source = $entity->getSource();
        $this->view->single = $entity->expose($this->getExpose());
        
        return $this->setRestResponse();
    }
    
    /**
     * Prepare a new model for the frontend
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function validateAction($id = null)
    {
        $model = $this->getModelClass();
        
        /** @var \Zemit\Mvc\Model $entity */
        $entity = $this->getSingle($id);
        $new = !$entity;
        
        if ($new) {
            $entity = new $model();
        }
        
        $entity->assign($this->getParams(), $this->getWhiteList(), $this->getColumnMap());
        
        /**
         * Event to run
         * @see https://docs.phalcon.io/4.0/en/db-models-events
         */
        $events = [
            'beforeCreate' => null,
            'beforeUpdate' => null,
            'beforeSave' => null,
            'beforeValidationOnCreate' => null,
            'beforeValidationOnUpdate' => null,
            'beforeValidation' => null,
            'prepareSave' => null,
            'validation' => null,
            'afterValidationOnCreate' => null,
            'afterValidationOnUpdate' => null,
            'afterValidation' => null,
        ];
        
        // run events, as it would normally
        foreach ($events as $event => $state) {
            $this->skipped = false;
            
            // skip depending wether it's a create or update
            if (strpos($event, $new ? 'Update' : 'Create') !== false) {
                continue;
            }
            
            // fire the event, allowing to fail or skip
            $events[$event] = $entity->fireEventCancel($event);
            if ($events[$event] === false) {
                // event failed
                break;
            }
            
            // event was skipped, just for consistencies purpose
            if ($this->skipped) {
                continue;
            }
        }
        
        $this->view->model = get_class($entity);
        $this->view->source = $entity->getSource();
        $this->view->single = $entity->expose($this->getExpose());
        $this->view->messages = $entity->getMessages();
        $this->view->events = $events;
        $this->view->validated = empty($this->view->messages);
        
        return $this->setRestResponse($this->view->validated);
    }
    
    /**
     * Saving a record
     * - Create
     * - Update
     *
     * @param null $id
     *
     * @return array
     */
    public function saveAction($id = null)
    {
        $this->view->setVars($this->save($id));
        
        return $this->setRestResponse($this->view->saved);
    }
    
    /**
     * Deleting a record
     *
     * @param null|int $id
     *
     * @return bool|\Phalcon\Http\ResponseInterface
     */
    public function deleteAction($id = null)
    {
        $single = $this->getSingle($id);
        
        $this->view->deleted = $single && $single->delete();
        $this->view->single = $single ? $single->expose($this->getExpose()) : false;
        $this->view->messages = $single ? $single->getMessages() : false;
        
        if (!$single) {
            $this->response->setStatusCode(404, 'Not Found');
            
            return false;
        }
        
        return $this->setRestResponse($this->view->deleted);
    }
    
    /**
     * Restoring record
     *
     * @param null $id
     *
     * @return bool|\Phalcon\Http\ResponseInterface
     */
    public function restoreAction($id = null)
    {
        $single = $this->getSingle($id);
        
        $this->view->restored = $single && $single->restore();
        $this->view->single = $single ? $single->expose($this->getExpose()) : false;
        $this->view->messages = $single ? $single->getMessages() : false;
        
        if (!$single) {
            $this->response->setStatusCode(404, 'Not Found');
    
            return false;
        }
        
        return $this->setRestResponse($this->view->restored);
    }
    
    /**
     * Re-ordering a position
     *
     * @param null $id
     * @param null $position
     *
     * @return bool|\Phalcon\Http\ResponseInterface
     */
    public function reorderAction($id = null)
    {
        $single = $this->getSingle($id);
        
        $position = $this->getParam('position', 'int');
        
        $this->view->reordered = $single ? $single->reorder($position) : false;
        $this->view->single = $single ? $single->expose($this->getExpose()) : false;
        $this->view->messages = $single ? $single->getMessages() : false;
        
        if (!$single) {
            $this->response->setStatusCode(404, 'Not Found');
            
            return false;
        }
        
        return $this->setRestResponse($this->view->reordered);
    }
}
