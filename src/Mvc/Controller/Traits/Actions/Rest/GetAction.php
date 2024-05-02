<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Mvc\Controller\Traits\Actions\Rest;

use Phalcon\Filter\Exception;
use Phalcon\Http\ResponseInterface;
use Zemit\Mvc\Controller\Traits\Abstracts\AbstractExpose;
use Zemit\Mvc\Controller\Traits\Abstracts\AbstractGetSingle;
use Zemit\Mvc\Controller\Traits\Abstracts\AbstractInjectable;
use Zemit\Mvc\Controller\Traits\Abstracts\AbstractRestResponse;

trait GetAction
{
    use AbstractExpose;
    use AbstractGetSingle;
    use AbstractInjectable;
    use AbstractRestResponse;
    
    /**
     * @deprecated Should use getAction() method instead
     * @throws Exception
     */
    public function getSingleAction(?int $id = null): ResponseInterface
    {
        return $this->getAction($id);
    }
    
    /**
     * Retrieving a single record
     * @throws Exception
     */
    public function getAction(?int $id = null): ResponseInterface
    {
        $entity = $this->getSingle($id);
        
        if (!$entity) {
            return $this->setRestErrorResponse(404);
        }
        
        $this->view->setVar('single', $this->expose($entity));
        return $this->setRestResponse(true);
    }
    
}