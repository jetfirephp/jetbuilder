<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;


/**
 * Class Language
 * @package Jet\Models
 * @Entity()
 * @Table(name="languages")
 */
class Language extends Model implements \JsonSerializable
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
    protected $name;
    /**
     * @Column(type="smallint", options={"default" : -1})
     */
    protected $state = 1;
    /**
     * @Column(type="string", unique=true)
     */
    protected $code;
    /**
     * @Column(type="string")
     */
    protected $locale;
    /**
     * @Column(type="string")
     */
    protected $date_format;

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
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

   
    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * @param mixed $date_format
     */
    public function setDateFormat($date_format)
    {
        $this->date_format = $date_format;
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
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'locale' => $this->getLocale(),
            'state' => $this->getState(),
            'date_format' => $this->getDateFormat()
        ];
    }
}
