<?php
namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Services\LoadFixture;

class LoadRole extends AbstractFixture
{

    use LoadFixture;

    protected $data = [
        '_user' => [
            'title' => 'user',
            'level' => -1
        ],
        '_super_admin' => [
            'title' => 'super_admin',
            'level' => 0
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadRole($manager);
    }
    
}