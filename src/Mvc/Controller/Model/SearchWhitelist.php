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
 * Trait SearchWhitelist
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Mvc\Controller\Model
 */
trait SearchWhitelist
{
    protected ?Collection $searchWhitelist;
    
    public function getSearchWhitelist(): array
    {
        return $this->searchWhitelist->get();
    }
    
    public function setSearchWhitelist($searchWhitelist): void
    {
        $this->searchWhitelist->set($searchWhitelist);
    }
    
    public function addSearchWhitelist(?array $searchWhitelist): int
    {
        return $this->searchWhitelist->add($searchWhitelist);
    }
    
    public function removeSearchWhitelist(?array $searchWhitelist): void
    {
        $this->searchWhitelist->remove($searchWhitelist);
    }
    
    public function resetSearchWhitelist(): void
    {
        $this->searchWhitelist->reset();
    }
}
