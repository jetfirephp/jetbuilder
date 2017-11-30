<?php
namespace Jet\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Jet\Services\LoadFixture;

class LoadSection extends AbstractFixture
{

    use LoadFixture;

    protected $data = [
        'basic_section' => [
            'section_id' => null,
            'section_class' => 'row',
            'style' => 'padding: 10px 0;',
            'container' => 'container'
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadSection($manager);
    }
}