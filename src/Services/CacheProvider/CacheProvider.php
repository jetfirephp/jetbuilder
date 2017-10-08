<?php

namespace Jet\Services\CacheProvider;


interface CacheProvider
{
    public function getCache($driver);
    public function getHandler($config);

}