<?php
namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Services\LoadFixture;

class LoadTemplate extends AbstractFixture
{
    use LoadFixture;

    protected $data = [
        'default_layout' => [
            'name' => 'DefaultGlobalFileLayout',
            'title' => 'Template de base',
            'path' => 'index',
            'website' => null
        ],
        'default_page_layout' => [
            'name' => 'DefaultPageFileLayout',
            'title' => 'Template de base pour une page',
            'path' => 'page',
            'website' => null
        ],
        'default_website_layout' => [
            'name' => 'DefaultGlobalFileWebsiteLayout',
            'title' => 'Template de base html',
            'path' => 'default_layout',
            'website' => null
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadTemplate($manager);
    }

}