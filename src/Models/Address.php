<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Address
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\AddressRepository")
 * @Table(name="addresses")
 * @HasLifecycleCallbacks
 */
class Address extends Model implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=100, options={"default" : "Mon adresse"})
     */
    protected $alias = 'Mon adresse';
    /**
     * @Column(type="string",length=100)
     */
    protected $address;
    /**
     * @Column(type="string",length=100)
     */
    protected $city;
    /**
     * @Column(type="string", length=32)
     */
    protected $postal_code;
    /**
     * @Column(type="string")
     */
    protected $country = "FRANCE";
    /**
     * @Column(type="float")
     */
    protected $latitude = 0;
    /**
     * @Column(type="float")
     */
    protected $longitude = 0;
    /**
     * @ManyToOne(targetEntity="Account")
     * @JoinColumn(name="account_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $account;
    
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
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param mixed $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    
    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }
    /**
     * @PrePersist
     */
    public function onChange(){
        $xy = geocode($this->getAddress().', '.$this->getPostalCode().' '.$this->getCity(). ', '.$this->getCountry());
        if(empty($xy))$xy = [1.6,2.8];
        $this->setLatitude($xy[0]);
        $this->setLongitude($xy[1]);
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
            'alias' => $this->getAlias(),
            'address' => $this->getAddress(),
            'city' => $this->getCity(),
            'postal_code' => $this->getPostalCode(),
            'country' => $this->getCountry(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }
}
