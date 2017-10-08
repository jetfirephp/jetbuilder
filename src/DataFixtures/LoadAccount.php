<?php
namespace Jet\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Jet\Services\LoadFixture;

class LoadAccount extends AbstractFixture implements DependentFixtureInterface
{
    use LoadFixture;

    protected $data = [
        [
            'firstName' => 'Sumugan',
            'lastName' => 'Sinnarasa',
            'password' => 'admin',
            'email' => 'sumugan.sinnarasa@desico.fr',
            'phone' => '06 52 01 15 31',
            'photo' => '/public/media/default/user-photo.png',
            'state' => 1,
            'status' => '_super_admin'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadAccount($manager);
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            'Jet\DataFixtures\LoadMedia',
            'Jet\DataFixtures\LoadStatus',
        ];
    }
}