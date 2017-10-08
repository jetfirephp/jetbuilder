<?php

namespace Jet\Services\CacheProvider;

use JetFire\Framework\Providers\Provider;

/**
 * Class MemcacheProvider
 * @package Jet\Services\CacheProvider
 */
class MemcacheProvider extends Provider implements CacheProvider
{

    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * @param \Memcache $memcache
     * @param string $host
     * @param int $port
     */
    public function init(\Memcache $memcache, $host = 'localhost', $port = 11211)
    {
        $this->memcache = $memcache;
        $this->memcache->connect($host, $port);
        $this->app->register($this->memcache);
    }

    /**
     * @return \Memcache
     */
    public function getMemcache()
    {
        return $this->memcache;
    }

    /**
     * @param $driver
     */
    public function getCache($driver)
    {
        $this->app->addRule($driver['class'], [
            'shared' => true,
            'call' => [
                'setMemcache' => [$this->memcache]
            ],
        ]);
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getHandler($config)
    {
        $args = array_merge([$this->memcache], $config['args']);
        $this->app->addRule($config['class'], [
            'shared' => true,
            'construct' => $args
        ]);
        return $this->app->get($config['class']);
    }

}