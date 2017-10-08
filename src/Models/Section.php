<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Account
 * @package Jet\Models
 * @Entity
 * @Table(name="sections")
 */
class Section extends Model implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=32, nullable=true)
     */
    protected $section_id;
    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $section_class;
    /**
     * @Column(type="string", length=200, nullable=true)
     */
    protected $style;
    /**
     * @Column(type="string", length=32, nullable=true)
     */
    protected $container;
    /**
     * @Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSectionId()
    {
        return $this->section_id;
    }

    /**
     * @param mixed $section_id
     */
    public function setSectionId($section_id)
    {
        $this->section_id = $section_id;
    }

    /**
     * @return mixed
     */
    public function getSectionClass()
    {
        return $this->section_class;
    }

    /**
     * @param mixed $section_class
     */
    public function setSectionClass($section_class)
    {
        $this->section_class = $section_class;
    }

    /**
     * @return mixed
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param mixed $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'section_id' => $this->getSectionId(),
            'section_class' => $this->getSectionClass(),
            'style' => $this->getStyle(),
            'container' => $this->getContainer(),
            'position' =>  $this->getPosition()
        ];
    }
}
