<?php

namespace Jet\Services\CacheProvider;

use JetFire\Framework\Providers\Provider;

/**
 * Class MemcachedProvider
 * @package Jet\Services\CacheProvider
 */
class MemcachedProvider extends Provider implements CacheProvider
{

    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * @param \Memcached $memcached
     * @param string $host
     * @param int $port
     */
    public function init(\Memcached $memcached, $host = 'localhost', $port = 11211)
    {
        $this->memcached = $memcached;
        $this->memcached->addServer($host, $port);
        $this->app->register($this->memcached);
    }

    /**
     * @return \Memcached
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * @param $driver
     */
    public function getCache($driver)
    {
        $this->app->addRule($driver['class'], [
            'shared' => true,
            'call' => [
                'setMemcached' => [$this->memcached]
            ],
        ]);
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getHandler($config)
    {
        $args = array_merge([$this->memcached], $config['args']);
        $this->app->addRule($config['class'], [
            'shared' => true,
            'construct' => $args
        ]);
        return $this->app->get($config['class']);
    }

}