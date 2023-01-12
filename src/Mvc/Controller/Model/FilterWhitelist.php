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
 * Trait Model
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait FilterWhitelist
{
    protected ?Collection $filterWhitelist;
    
    public function getFilterWhitelist(): array
    {
        return $this->filterWhitelist->get();
    }
    
    public function setFilterWhitelist($filterWhitelist): void
    {
        $this->filterWhitelist->set($filterWhitelist);
    }
    
    public function addFilterWhitelist(?array $filterWhitelist): int
    {
        return $this->filterWhitelist->add($filterWhitelist);
    }
    
    public function removeFilterWhitelist(?array $filterWhitelist): void
    {
        $this->filterWhitelist->remove($filterWhitelist);
    }
    
    public function resetFilterWhitelist(): void
    {
        $this->filterWhitelist->reset();
    }
}
