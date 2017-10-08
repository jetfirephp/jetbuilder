<?php
namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Models\Template;

class LoadTemplate extends AbstractFixture
{

    protected $data = [
        'default_layout' => [
            'name' => 'DefaultGlobalFileLayout',
            'title' => 'Template de base',
            'content' => 'index',
            'website' => null,
            'category' => 'layout',
            'scope' => 'global',
            'type' => 'file'
        ],
        'default_page_layout' => [
            'name' => 'DefaultPageFileLayout',
            'title' => 'Template de base pour une page',
            'content' => 'page',
            'website' => null,
            'category' => 'layout',
            'scope' => 'global',
            'type' => 'file'
        ],
        'default_website_layout' => [
            'name' => 'DefaultGlobalFileWebsiteLayout',
            'title' => 'Template de base html',
            'content' => 'default_layout',
            'website' => null,
            'category' => 'layout',
            'scope' => 'global',
            'type' => 'file'
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach($this->data as $key => $data){
            $template = (Template::where('name', $data['name'])->count() == 0) ? new Template() : Template::findOneByName($data['name']);
            $template->setName($data['name']);
            $template->setTitle($data['title']);
            $template->setContent($data['content']);
            $template->setCategory($data['category']);
            $template->setScope($data['scope']);
            $template->setType($data['type']);
            $this->setReference($key,$template);
            $manager->persist($template);
        }
        $manager->flush();
    }

}