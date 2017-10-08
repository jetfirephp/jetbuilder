<?php
namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Services\LoadFixture;

class LoadLibrary extends AbstractFixture
{

    use LoadFixture;

    protected $data = [
        [
            'name' => 'Jquery',
            'path' => 'libs/jquery/jquery.min.js',
            'type' => 'file',
            'category' => 'js'
        ],
        [
            'name' => 'Bootstrap Js',
            'path' => 'libs/bootstrap/bootstrap.min.js',
            'type' => 'file',
            'category' => 'js'
        ],
        [
            'name' => 'Bootstrap Css',
            'path' => 'libs/bootstrap/bootstrap.min.css',
            'type' => 'file',
            'category' => 'css'
        ],
        [
            'name' => 'Material Js',
            'path' => 'libs/material/materialize.min.js',
            'type' => 'file',
            'category' => 'js'
        ],
        [
            'name' => 'Material Css',
            'path' => 'libs/material/materialize.min.css',
            'type' => 'file',
            'category' => 'css'
        ],
        [
            'name' => 'Font Awesome',
            'path' => '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
            'type' => 'cdn',
            'category' => 'css'
        ],
        [
            'name' => 'Owl Carousel Js',
            'path' => 'libs/owl/owl.carousel.min.js',
            'type' => 'file',
            'category' => 'js'
        ],
        [
            'name' => 'Owl Carousel Css',
            'path' => 'libs/owl/owl.carousel.css',
            'type' => 'file',
            'category' => 'css'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadLibrary($manager);
    }
}