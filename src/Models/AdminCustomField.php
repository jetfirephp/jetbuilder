<?php

namespace Jet\Models;

use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class AdminCustomField
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\AdminCustomFieldRepository")
 * @Table(name="admin_custom_fields")
 */
class AdminCustomField extends Model implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string")
     */
    protected $title;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @Column(type="text", nullable=true)
     */
    protected $description;
    /**
     * @Column(type="string")
     */
    protected $type;
    /**
     * @Column(type="integer")
     */
    protected $position;
    /**
     * @Column(type="integer")
     */
    protected $access_level = 4;
    /**
     * @Column(type="boolean")
     */
    protected $required = false;
    /**
     * @OneToMany(targetEntity="AdminCustomField", mappedBy="parent")
     */
    protected $children;
    /**
     * @ManyToOne(targetEntity="AdminCustomField", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id" , nullable=true, onDelete="CASCADE")
     */
    protected $parent;
    /**
     * @ManyToOne(targetEntity="CustomField", inversedBy="fields")
     * @JoinColumn(name="custom_field_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $custom_field;
    /**
     * @Column(type="json_array")
     */
    protected $data = [];
    /**
     * @Column(type="json_array")
     */
    protected $content = [];

    /**
     * AdminCustomField constructor.
     */
    public function __construct() {
        $this->children = new ArrayCollection();
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
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
     * @return mixed
     */
    public function getAccessLevel()
    {
        return $this->access_level;
    }

    /**
     * @param mixed $access_level
     */
    public function setAccessLevel($access_level)
    {
        $this->access_level = $access_level;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }

    /**
     * @param CustomField $children
     */
    public function addChildren(CustomField $children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return CustomField
     */
    public function getCustomField()
    {
        return $this->custom_field;
    }

    /**
     * @param CustomField $custom_field
     */
    public function setCustomField(CustomField $custom_field)
    {
        $this->custom_field = $custom_field;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
            'title' => $this->getTitle(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'required' => $this->isRequired(),
            'children' => $this->getChildren(),
            'parent' => $this->getParent(),
            'custom_field' => $this->getCustomField(),
            'data' => $this->getData(),
            'position' => $this->getPosition(),
            'access_level' => $this->getAccessLevel(),
            'content' => $this->getContent()
        ];
    }
}