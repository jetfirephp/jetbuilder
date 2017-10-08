<?php
namespace Jet\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Jet\Models\Section;

class LoadSection extends AbstractFixture
{

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
        foreach($this->data as $key => $data) {
            $section = (Section::where('container', $data['container'])->count() == 0) 
                ? new Section() : Section::findOneByContainer($data['container']);
            $section->setSectionId($data['section_id']);
            $section->setSectionClass($data['section_class']);
            $section->setStyle($data['style']);
            $section->setContainer($data['container']);
            $this->addReference($key, $section);
            $manager->persist($section);
        }
        $manager->flush();
    }
}