<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Traits\Abstracts\Query;

use Phalcon\Support\Collection;

trait AbstractColumn
{
    abstract public function initializeColumn(): void;
    
    abstract public function setColumn(?Collection $column): void;
    
    abstract public function getColumn(): ?Collection;
}
