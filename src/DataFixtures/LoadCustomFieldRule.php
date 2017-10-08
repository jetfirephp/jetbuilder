<?php

namespace Jet\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Jet\Models\CustomFieldRule;

class LoadCustomFieldRule extends AbstractFixture
{
    protected $data = [
        'global_rule' => [
            'title' => 'Global',
            'name' => 'global',
            'type' => 'global',
            'table' => null,
            'callback' => null,
        ],
        'everywhere_rule' => [
            'title' => 'Partout',
            'name' => 'everywhere',
            'type' => 'global',
            'table' => null,
            'callback' => null,
        ],
        'publication_type_rule' => [
            'title' => 'Type de publication',
            'name' => 'publication_type',
            'type' => 'global',
            'table' => null,
            'callback' => '/website/list-rule-value',
        ],
        'page_rule' => [
            'title' => 'Page',
            'name' => 'page',
            'type' => 'single',
            'table' => 'pages',
            'callback' => '/page/list-rule-value',
        ],
        'model_rule' => [
            'title' => 'Modèle de page',
            'name' => 'model',
            'type' => 'single',
            'table' => 'templates',
            'callback' => '/template/list-rule-value',
        ],
        'page_type_rule' => [
            'title' => 'Type de page',
            'name' => 'page_type',
            'type' => 'global',
            'table' => null,
            'callback' => '/page/list-type-rule-value',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach($this->data as $key => $data) {
            $cf = (CustomFieldRule::where('name', $data['name'])->count() == 0) ? new CustomFieldRule() : CustomFieldRule::findOneByName($data['name']);
            $cf->setTitle($data['title']);
            $cf->setName($data['name']);
            $cf->setCallback($data['callback']);
            $cf->setType($data['type']);
            $cf->setReplaceTable($data['table']);
            $this->setReference($key, $cf);
            $manager->persist($cf);
        }
        $manager->flush();
    }
}