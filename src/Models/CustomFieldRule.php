<?php

namespace Jet\Models;

use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class CustomFieldRule
 * @package Jet\Models
 * @Entity
 * @Table(name="custom_field_rules")
 */
class CustomFieldRule extends Model implements \JsonSerializable
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
     * @Column(type="string", length=30, unique=true)
     */
    protected $name;
    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $callback;
    /**
     * @Column(type="string")
     */
    protected $type = 'global';
    /**
     * @Column(type="string", nullable=true)
     */
    protected $replace_table;

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
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
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
    public function getReplaceTable()
    {
        return $this->replace_table;
    }

    /**
     * @param mixed $replace_table
     */
    public function setReplaceTable($replace_table)
    {
        $this->replace_table = $replace_table;
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
            'callback' => $this->getCallback(),
            'type' => $this->getType(),
            'replace_table' => $this->getReplaceTable()
        ];
    }
}