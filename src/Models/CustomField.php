<?php

namespace Jet\Models;

use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class CustomField
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\CustomFieldRepository")
 * @Table(name="custom_fields")
 */
class CustomField extends Model implements \JsonSerializable
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
     * @ManyToOne(targetEntity="CustomFieldRule")
     * @JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;
    /**
     * @Column(type="string", length=2, nullable=true)
     */
    protected $operation;
    /**
     * @Column(type="string", length=30, nullable=true)
     */
    protected $value;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $website;
    /**
     * @OneToMany(targetEntity="AdminCustomField", mappedBy="custom_field")
     */
    protected $fields;
    /**
     * @Column(type="smallint")
     */
    protected $access_level = 4;

    /**
     * CustomField constructor.
     */
    public function __construct() {
        $this->fields = new ArrayCollection();
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
     * @return CustomFieldRule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param CustomFieldRule $rule
     */
    public function setRule(CustomFieldRule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

   
    /**
     * @return Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param Website $website
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param AdminCustomField $field
     */
    public function addField(AdminCustomField $field)
    {
        $this->fields[] = $field;
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
            'operation' => $this->getOperation(),
            'value' => $this->getValue(),
            'fields' => $this->getFields(),
            'rule' => $this->getRule(),
            'access_level' => $this->getAccessLevel()
        ];
    }
}