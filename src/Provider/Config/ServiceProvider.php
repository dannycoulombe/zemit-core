<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Provider\Config;

use Phalcon\Di\DiInterface;
use Zemit\Bootstrap;
use Zemit\Bootstrap\Config;
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
 * @package Zemit\Provider\Config
 */
class ServiceProvider extends AbstractServiceProvider
{
    protected $serviceName = 'config';
    
    /**
     * {@inheritdoc}
     *
     * @param DiInterface $di
     */
    public function register(DiInterface $di = null): void
    {
        // Set shared service in DI
        $di->setShared($this->getName(), function() use ($di) {
            
            /** @var Bootstrap $bootstrap */
            $bootstrap = $di->get('bootstrap');
            
            $config = $bootstrap->config ?? new Config();
            if (is_string($config) && class_exists($config)) {
                $config = new $config();
            }
            
            // Inject some dynamic variables
            $config->mode = $di->get('bootstrap')->getMode();
            
            // Merge config with current environment
            $config->mergeEnvConfig();
            
            // Launch bootstrap prepare raw php configs
            $bootstrap->prepare->php($config->path('app'));
            
            // Register other providers
//            foreach ($config->providers as $provider) {
//                $di->register(new $provider($di));
//            }
            
            // Set the config
            return $config;
        });
    }
}
