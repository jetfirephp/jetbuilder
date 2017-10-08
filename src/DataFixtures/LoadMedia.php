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
            'path' => '/public/media/default/user-photo.png',
            'type' => 'image/png',
            'size' => 16392,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Default account photo'
        ],
        [
            'title' => 'Default favicon',
            'path' => '/public/media/default/favicon.ico',
            'type' => 'image/ico',
            'size' => 16392,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Default favicon'
        ],

        [
            'title' => 'Article 1',
            'path' => '/public/media/default/post/article-1.jpg',
            'type' => 'image/jpg',
            'size' => 10.86,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Article 1'
        ],
        [
            'title' => 'Article 2',
            'path' => '/public/media/default/post/article-2.jpg',
            'type' => 'image/jpg',
            'size' => 2577,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Article 2'
        ],
        [
            'title' => 'Article 3',
            'path' => '/public/media/default/post/article-3.jpg',
            'type' => 'image/jpg',
            'size' => 2577,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Article 3'
        ],
        [
            'title' => 'Article 4',
            'path' => '/public/media/default/post/article-4.jpg',
            'type' => 'image/jpg',
            'size' => 2577,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Article 4'
        ],
        [
            'title' => 'Article 5',
            'path' => '/public/media/default/post/article-5.jpg',
            'type' => 'image/jpg',
            'size' => 2577,
            'access_level' => 4,
            'scope' => 'global',
            'alt' => 'Article 5'
        ],
    ];
    
    public function load(ObjectManager $manager)
    {
        $this->loadMedia($manager);
    }
}