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
 * Trait Whitelist
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait Whitelist
{
    protected ?Collection $whitelist;
    
    public function getWhitelist(): array
    {
        return $this->whitelist->get();
    }
    
    public function setWhitelist($whitelist): void
    {
        $this->whitelist->set($whitelist);
    }
    
    public function addWhitelist(?array $whitelist): int
    {
        return $this->whitelist->add($whitelist);
    }
    
    public function removeWhitelist(?array $whitelist): void
    {
        $this->whitelist->remove($whitelist);
    }
    
    public function resetWhitelist(): void
    {
        $this->whitelist->reset();
    }
}
