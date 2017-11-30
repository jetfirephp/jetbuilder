<?php

namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Services\LoadFixture;

class LoadMedia extends AbstractFixture
{
    use LoadFixture;

    protected $data = [
        [
            'title' => 'Default account photo',
            'path' => '/public/upload/default/user-photo.png',
            'type' => 'image/png',
            'size' => 16392,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Default account photo'
        ],
        [
            'title' => 'Default favicon',
            'path' => '/public/upload/default/favicon.ico',
            'type' => 'image/ico',
            'size' => 16392,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Default favicon'
        ]
    ];
    
    public function load(ObjectManager $manager)
    {
        $this->loadMedia($manager);
    }
}