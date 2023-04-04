<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Models\Interfaces;

use Phalcon\Mvc\ModelInterface;

interface RoleInterface extends AbstractInterface
{
    public function getIndex();
    
    public function setIndex(string $index);
    
    public function setLabel(string $label);
}