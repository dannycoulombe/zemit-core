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

/**
 * Trait Export
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait Export
{
    /**
     * Get columns merge definition for export
     *
     * @return null|array
     */
    public function getExportMergeColum()
    {
        return null;
    }
    
    /**
     * Get columns format field text definition for export
     *
     * @param array|null $params
     *
     * @return null|array
     */
    public function getExportFormatFieldText(?array $params = null)
    {
        return null;
    }
    
    /**
     * @param array|null $array
     *
     * @return array|null
     */
    public function flatternArrayForCsv(?array &$list = null)
    {
        
        foreach ($list as $listKey => $listValue) {
            foreach ($listValue as $column => $value) {
                if (is_array($value) || is_object($value)) {
                    $value = $this->concatListFieldElementForCsv($value, ' ');
                    $list[$listKey][$column] = $this->arrayFlatten($value, $column);
                    if (is_array($list[$listKey][$column])) {
                        foreach ($list[$listKey][$column] as $childKey => $childValue) {
                            $list[$listKey][$childKey] = $childValue;
                            unset ($list[$listKey][$column]);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * @param array|object $list
     * @param string|null $seperator
     *
     * @return array|object
     */
    public function concatListFieldElementForCsv($list, $seperator = ' ')
    {
        foreach ($list as $valueKey => $element) {
            if (is_array($element) || is_object($element)) {
                $lastKey = array_key_last($list);
                if ($valueKey === $lastKey) {
                    continue;
                }
                foreach ($element as $elKey => $elValue) {
                    $list[$lastKey][$elKey] .= $seperator . $elValue;
                    if ($lastKey != $valueKey) {
                        unset($list[$valueKey]);
                    }
                }
            }
        }
        
        return $list;
    }
    
    /**
     * @param array|null $array
     * @param string|null $alias
     *
     * @return array|null
     */
    function arrayFlatten(?array $array, ?string $alias = null)
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return = array_merge($return, $this->arrayFlatten($value, $alias));
            }
            else {
                $return[$alias . '.' . $key] = $value;
            }
        }
        return $return;
    }
    
    /**
     * @param array|null $listValue
     *
     * @return array|null
     */
    public function mergeColumns(?array $listValue)
    {
        $columnToMergeList = $this->getExportMergeColum();
        if (!$columnToMergeList || empty($columnToMergeList)) {
            return $listValue;
        }
        
        $columnList = [];
        foreach ($columnToMergeList as $columnToMerge) {
            foreach ($columnToMerge['columns'] as $column) {
                if (isset($listValue[$column])) {
                    $columnList[$columnToMerge['name']][] = $listValue[$column];
                    unset($listValue[$column]);
                }
            }
            $listValue[$columnToMerge['name']] = implode(' ', $columnList[$columnToMerge['name']] ?? []);
        }
        
        return $listValue;
    }
    
    /**
     * @param array|null $list
     *
     * @return array|null
     */
    public function formatColumnText(?array &$list)
    {
        foreach ($list as $listKey => $listValue) {
            
            $mergeColumArray = $this->mergeColumns($listValue);
            if (!empty($mergeColumArray)) {
                $list[$listKey] = $mergeColumArray;
            }
            
            $formatArray = $this->getExportFormatFieldText($listValue);
            if ($formatArray) {
                $columNameList = array_keys($formatArray);
                foreach ($formatArray as $formatKey => $formatValue) {
                    if (isset($formatValue['text'])) {
                        $list[$listKey][$formatKey] = $formatValue['text'];
                    }
                    
                    if (isset($formatValue['rename'])) {
                        
                        $list[$listKey][$formatValue['rename']] = $formatValue['text'] ?? ($list[$listKey][$formatKey] ?? null);
                        if ($formatValue['rename'] !== $formatKey) {
                            foreach ($columNameList as $columnKey => $columnValue) {
                                
                                if ($formatKey === $columnValue) {
                                    $columNameList[$columnKey] = $formatValue['rename'];
                                }
                            }
                            
                            unset($list[$listKey][$formatKey]);
                        }
                    }
                }
                
                if (isset($formatArray['reorderColumns']) && $formatArray['reorderColumns']) {
                    $list[$listKey] = $this->arrayCustomOrder($list[$listKey], $columNameList);
                }
            }
        }
        
        return $list;
    }
    
    /**
     * @param array $arrayToOrder
     * @param array $orderList
     *
     * @return array
     */
    function arrayCustomOrder($arrayToOrder, $orderList)
    {
        $ordered = [];
        foreach ($orderList as $key) {
            if (array_key_exists($key, $arrayToOrder)) {
                $ordered[$key] = $arrayToOrder[$key];
            }
        }
        return $ordered;
    }
}
