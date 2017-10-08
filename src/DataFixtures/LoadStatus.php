<?php
namespace Jet\DataFixtures;

use Jet\Models\Status;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadStatus extends AbstractFixture
{

    protected $data = [
        '_super_admin' => [
            'super_admin', 0
        ],
        '_admin' => [
            'admin', 1
        ],
        '_webmaster' => [
            'webmaster', 2
        ],
        '_commercial' => [
            'commercial', 2
        ],
        '_user' => [
            'user', 4
        ],
        '_ikosoft' => [
            'ikosoft', 3
        ],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach($this->data as $key => $role){
            $status = (Status::where('role', $role[0])->count() == 0) ? new Status() : Status::findOneByRole($role[0]);
            $status->setRole($role[0]);
            $status->setLevel($role[1]);
            $manager->persist($status);
            $this->addReference($key, $status);
        }
        $manager->flush();
    }
    
}