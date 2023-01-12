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

use ArrayIterator;

/**
 * Collection
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
class Collection
{
    protected array $data = [];
    
    public function __construct($data)
    {
        $this->set($data);
    }
    
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }
    
    public function get()
    {
        return $this->data;
    }
    
    public function set(?array $data = null): void
    {
        $this->data = $data;
    }
    
    public function add(?array $data): int
    {
        return array_push($this->data, ...$data);
    }
    
    public function remove(?array $data): void
    {
        $this->data = array_diff($data, $this->data);
    }
    
    public function reset()
    {
        $this->data = [];
    }
}
