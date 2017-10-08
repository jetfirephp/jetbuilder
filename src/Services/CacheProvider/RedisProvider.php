<?php

namespace Jet\Services\CacheProvider;

use JetFire\Framework\Providers\Provider;

/**
 * Class RedisProvider
 * @package Jet\Services\CacheProvider
 */
class RedisProvider extends Provider implements CacheProvider
{

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * @param \Redis $redis
     * @param string $host
     * @param int $port
     */
    public function init(\Redis $redis, $host = 'localhost', $port = 6379)
    {
        $this->redis = $redis;
        $this->redis->connect($host, $port);
        $this->app->register($this->redis);
    }

    /**
     * @return \Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * @param $driver
     * @return mixed|null
     */
    public function getCache($driver)
    {
        if (isset($driver['class'])) {
            $this->app->addRule($driver['class'], [
                'shared' => true,
                'call' => [
                    'setRedis' => [$this->redis]
                ],
            ]);
            return $this->app->get($driver['class']);
        }
        return null;
    }

    /**
     * @param $config
     * @return mixed|null
     */
    public function getHandler($config)
    {
        if (isset($config['class']) && isset($config['args'])) {
            $args = array_merge([$this->redis], $config['args']);
            $this->app->addRule($config['class'], [
                'shared' => true,
                'construct' => $args
            ]);
            return $this->app->get($config['class']);
        }
        return null;
    }

}