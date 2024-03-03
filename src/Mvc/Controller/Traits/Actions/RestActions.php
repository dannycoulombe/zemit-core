<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Traits\Actions;

use Zemit\Mvc\Controller\Traits\Actions\Rest\CountAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\DeleteAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\ExportAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\GetAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\GetListAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\IndexAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\NewAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\ReorderAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\RestoreAction;
use Zemit\Mvc\Controller\Traits\Actions\Rest\SaveAction;

trait RestActions
{
    use CountAction;
    use DeleteAction;
    use ExportAction;
    use GetAction;
    use GetListAction;
    use IndexAction;
    use NewAction;
    use ReorderAction;
    use RestoreAction;
    use SaveAction;
}