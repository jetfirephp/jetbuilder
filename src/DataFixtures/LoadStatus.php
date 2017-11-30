<?php
namespace Jet\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Jet\Services\LoadFixture;

class LoadStatus extends AbstractFixture
{

    use LoadFixture;

    protected $data = [
        '_user' => [
            'role' => 'user',
            'level' => -1
        ],
        '_super_admin' => [
            'role' => 'super_admin',
            'level' => 0
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadStatus($manager);
    }
    
}