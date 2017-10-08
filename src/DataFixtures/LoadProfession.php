<?php

namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Models\Profession;

class LoadProfession extends AbstractFixture
{
    protected $data = [
        [
            'name' => 'Coiffure',
            'slug' => 'barber',
            'icon' => 'fa fa-scissors'
        ],
        [
            'name' => 'Institut de beauté',
            'slug' => 'spa',
            'icon' => 'fa fa-scissors'
        ],
        [
            'name' => 'Sport',
            'slug' => 'sport',
            'icon' => 'fa fa-trophy'
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            $profession = (Profession::where('slug', $data['slug'])->count() == 0) ? new Profession() : Profession::findOneBySlug($data['slug']);
            $profession->setName($data['name']);
            $profession->setSlug($data['slug']);
            $profession->setIcon($data['icon']);
            $this->setReference($data['slug'], $profession);
            $manager->persist($profession);
        }
        $manager->flush();
    }
}