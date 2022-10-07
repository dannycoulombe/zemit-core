<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Model\Expose;

use Zemit\Mvc\Model;
use Zemit\Utils\Multibyte;
use Phalcon\Support\HelperFactory;
use Zemit\Utils\Sprintf;

/**
 * Class Expose
 * @todo rewrite this code
 * @todo write unit test for this
 *
 * Example
 *
 * Simple
 * ```php
 * $this->expose() // expose everything except protected properties
 * $this->expose(null, true, true); // expose everything including protected properties
 * $this->expose(array('Source.id' => false)) // expose everything except 'id' and protected properties
 * $this->expose(array('Source' => array('id', 'title')) // expose only 'id' and 'title'
 * $this->expose(array('Source' => true, false) // expose everything from Source except protected properties
 * $this->expose(array('Source.Sources' => true, false) // expose everything from Source->Sources except protected properties
 * $this->expose(array('Source.Sources' => array('id'), false) // expose only the 'id' field of the sub array "Sources"
 * $this->expose(array('Source.Sources' => array(true, 'id' => false), false) // expose everything from the sub array "Sources" except the 'id' field
 * $this->expose(array('Source' => array(false, 'Sources' => array(true, 'id' => false))) // expose everything from the sub array "Sources" except the 'id' field
 * ```
 * Complexe
 *
 *
 * @package Zemit\Mvc\Model\Expose
 */
trait Expose
{
    /**
     * @param array|null $columns
     * @param bool $expose
     * @param bool $protected
     *
     * @return array|false|Model
     */
    public function expose(?array $columns = null, bool $expose = true, bool $protected = false)
    {
        $builder = new Builder();
        $builder->setColumns(self::_parseColumnsRecursive($columns));
        $builder->setExpose($expose);
        $builder->setProtected($protected);
        $builder->setParent($this);
        $builder->setValue($this);
        $builder->setKey(trim(mb_strtolower((new HelperFactory)->camelize($this->getSource()))));
        
        return self::_expose($builder);
    }
    
    /**
     * @param $string
     * @param $value
     *
     * @return false|string
     */
    private static function _getValue(string $string, $value)
    {
        $ret = null;
        if (is_array($value) || is_object($value)) {
            $ret = Sprintf::sprintfn($string, $value);
        }
        else {
            $ret = Sprintf::mb_sprintf($string, $value);
        }
        
        return $ret;
    }
    
    private static function _checkExpose(Builder $builder): void
    {
        $columns = $builder->getColumns();
        $fullKey = $builder->getFullKey();
        $value = $builder->getValue();
        
        // Check if the key itself exists at first
        if (isset($columns[$fullKey])) {
            $column = $columns[$fullKey];
            
            // If boolean, set expose to the bolean value
            if (is_bool($column)) {
                $builder->setExpose($column);
            }
            
            // If callable, set the expose to true, and run the method and passes the builder as parameter
            else if (is_callable($column)) {
                $builder->setExpose(true);
                $callbackReturn = $column($builder);
                
                // If builder is returned, no nothing
                if ($callbackReturn instanceof BuilderInterface) {
                    $builder = $callbackReturn;
                }
                
                // If string is returned, set expose to true and parse with mb_sprintfn or mb_sprintf
                else if (is_string($callbackReturn)) {
                    $builder->setExpose(true);
                    $builder->setValue(self::_getValue($callbackReturn, $value));
                }
                
                // If bool is returned, set expose to boolean value
                else if (is_bool($callbackReturn)) {
                    $builder->setExpose($callbackReturn);
                }
                
                // If array is returned, parse the columns from the current context key and merge it with the builder
                else if (is_array($callbackReturn)) {
                    $columns = self::_parseColumnsRecursive($callbackReturn, $builder->getFullKey());
                    
                    // If not set, set expose to false by default
                    if (!isset($columns[$builder->getFullKey()])) {
                        $columns[$builder->getFullKey()] = false;
                    }
                    
                    //@TODO handle with array_merge_recursive and handle array inside the columns parameters ^^
                    $builder->setColumns(array_merge($builder->getColumns(), $columns));
                }
            }
            
            // If string, set expose to true and parse with mb_sprintfn or mb_sprintf
            else if (is_string($column)) {
                $builder->setExpose(true);
                $builder->setValue(self::_getValue($column, $value));
            }
        }
        
        // Otherwise, check if a parent key exists
        else {
            $parentKey = $fullKey;
            
            while($parentIndex = strrpos($parentKey, '.')) {
                $parentKey = substr($parentKey, 0, $parentIndex);
                
                if (isset($columns[$parentKey])) {
                    $column = $columns[$parentKey];
                    
                    if (is_bool($column)) {
                        $builder->setExpose($column);
                    }
                    else if (is_callable($column)) {
                        $builder->setExpose(true);
                        $callbackReturn = $column($builder);
                        if ($callbackReturn instanceof BuilderInterface) {
                            $builder = $callbackReturn;
                        }
                        else if (is_string($callbackReturn)) {
                            $builder->setExpose(true);
                            $builder->setValue(self::_getValue($callbackReturn, $value));
                        }
                        else if (is_bool($callbackReturn)) {
                            $builder->setExpose($callbackReturn);
                        }
                        else if (is_array($callbackReturn)) {
                            // Since its a parent, we're not supposed to re-re-merge the existings columns
                        }
                    }
                    else if (is_string($column)) {
                        $builder->setExpose(false);
                        $builder->setValue(self::_getValue($column, $value));
                    }
                    break; // break at the first parent found
                }
            }
        }
        
        // Try to find a subentity, or field that has the true value
        $value = $builder->getValue();
        if ((is_array($value) || is_object($value) || is_callable($value))) {
            $subColumns = is_null($columns) ? $columns : array_filter($columns, function($columnValue, $columnKey) use ($builder) {
                $ret = strpos($columnKey, $builder->getFullKey()) === 0;
                if ($ret && $columnValue === true) {
                    // expose the current instance (which is the parent of the subcolumn)
                    $builder->setExpose(true);
                }
                
                return $ret;
            }, ARRAY_FILTER_USE_BOTH);
        }
        
        // check for protected setting
        $key = $builder->getKey();
        if (!$builder->getProtected() && is_string($key) && strpos($key, '_') === 0) {
            $builder->setExpose(false);
        }
    }
    
    /**
     * @param Builder $builder
     *
     * @return array|false|Model
     */
    private static function _expose(Builder $builder)
    {
        $builder = clone $builder;
        $columns = $builder->getColumns();
        
        /** @var Model $value */
        $value = $builder->getValue();
        
        if (is_array($value) || is_object($value)) {
            $toParse = [];
            if (is_array($value)) {
                $toParse = $value;
            }
            else if (method_exists($value, 'toArray')) {
                $toParse = $value->toArray();
            }
            
            // @TODO fix / refactor this
            if (isset($value->dirtyRelated)) {
                foreach ($value->dirtyRelated as $dirtyRelatedKey => $dirtyRelated) {
                    $toParse[$dirtyRelatedKey] = $dirtyRelated ?? false;
                }
            }
            
            // si aucune column demandé et que l'expose est à false
            if (is_null($columns) && !$builder->getExpose()) {
                return [];
            }
            
            // Prépare l'array de retour des fields de l'instance
            $ret = [];
            $builder->setContextKey($builder->getFullKey());
            foreach ($toParse as $fieldKey => $fieldValue) {
                $builder->setParent($value);
                $builder->setKey($fieldKey);
                $builder->setValue($fieldValue);
                self::_checkExpose($builder);
                if ($builder->getExpose()) {
                    $ret [$fieldKey] = self::_expose($builder);
                }
            }
        }
        else {
            $ret = $builder->getExpose() ? $value : false;
        }
        
        return $ret;
    }
    
    /**
     * Here to parse the columns parameter into some kind of flatten array with
     * the key path separated by dot "my.path" and the value true, false or a callback function
     * including the ExposeBuilder object
     *
     * @param array|null $columns
     * @param string|null $context
     *
     * @return array|null
     */
    public static function _parseColumnsRecursive(?array $columns = null, ?string $context = null) : ?array
    {
        if (!isset($columns)) {
            return null;
        }
        $ret = [];
        if (!is_array($columns) || is_object($columns)) {
            $columns = [$columns];
        }
        foreach ($columns as $key => $value) {
            if (is_bool($key)) {
                $value = $key;
                $key = null;
            }
            
            if (is_int($key)) {
                if (is_string($value)) {
                    $key = $value;
                    $value = true;
                }
                else {
                    $key = null;
                }
            }
            
            if (is_string($key)) {
                $key = trim(mb_strtolower($key));
            }
            
            if (is_string($value) && empty($value)) {
                $value = true;
            }
            $currentKey = (!empty($context) ? $context . (!empty($key) ? '.' : null) : null) . $key;
            
            if (is_array($value) || is_object($value)) {
                if (is_callable($value)) {
                    $ret[$currentKey] = $value;
                }
                else {
                    $subRet = self::_parseColumnsRecursive($value, $currentKey);
                    $ret = array_merge_recursive($ret, $subRet);
                    
                    if (!isset($ret[$currentKey])) {
                        $ret[$currentKey] = false;
                    }
                }
            }
            else {
                $ret[$currentKey] = $value;
            }
        }
        
        return $ret;
    }
}
