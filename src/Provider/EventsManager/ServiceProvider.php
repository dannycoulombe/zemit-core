<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Provider\EventsManager;

use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Zemit\Provider\AbstractServiceProvider;

/**
 * Class ServiceProvider
 *
 * @author Julien Turbide <jturbide@nuagerie.com>
 * @copyright Zemit Team <contact@zemit.com>
 *
 * @since 1.0
 * @version 1.0
 *
 * @package Zemit\Provider\EventsManager
 */
class ServiceProvider extends AbstractServiceProvider
{
    protected $serviceName = 'eventsManager';
    
    /**
     * {@inheritdoc}
     *
     * @param DiInterface $di
     */
    public function register(DiInterface $di): void
    {
        $di->setShared($this->getName(), function() {
            $eventsManager = new Manager();
            $eventsManager->enablePriorities(true);
            return $eventsManager;
        });
    }
}
