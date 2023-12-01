<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Behavior\Skip;

class SkipCache
{
    /**
     * Stop operation
     * @return false
     */
    public function getCache(): bool
    {
        return false;
    }
}
