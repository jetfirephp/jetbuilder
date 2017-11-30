<?php

namespace Jet\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Jet\Services\LoadFixture;

class LoadRoute extends AbstractFixture
{
    use LoadFixture;

    protected $data = [
        [
            'url' => '/',
            'name' => 'home',
            'method' => ['GET'],
            'argument' => null,
            'middleware' => null,
            'subdomain' => null,
            'position' => 0
        ]
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadRoute($manager);
    }

}