<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Provider\ModelsMetadata;

use Phalcon\Cache\AdapterFactory;
use Phalcon\Mvc\Model\MetaData\Memory;
use Phalcon\Mvc\Model\MetaData\Stream;
use Phalcon\Storage\SerializerFactory;
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
 * @package Zemit\Provider\ModelsMetadata
 */
class ServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'modelsMetadata';
    
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register(\Phalcon\Di\DiInterface $di): void
    {
        $di->setShared($this->getName(), function() use ($di) {
            
            $config = $di->get('config')->metadata;
            $driverName = $di->get('bootstrap')->getMode() === 'console'? 'cli' : 'driver';
            $driver = $config->drivers->{$config->$driverName};
            $adapter = $driver->adapter;
            
            $options = array_merge($config->default->toArray(), $driver->toArray());
            if (in_array($adapter, [Memory::class, Stream::class])) {
                return new $adapter($options);
            }
            
            $serializerFactory = new SerializerFactory();
            $adapterFactory = new AdapterFactory($serializerFactory);
            
            return new $adapter($adapterFactory, $options);
        });
    }
}
