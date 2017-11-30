<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Account
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\AccountRepository")
 * @Table(name="accounts")
 * @HasLifecycleCallbacks
 */
class Account extends Model implements \JsonSerializable
{

    /**
     * @var null
     */
    private $tmp_id = null;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=100, nullable=true)
     */
    public $first_name;
    /**
     * @Column(type="string", length=100, nullable=true)
     */
    public $last_name;
    /**
     * @Column(type="string")
     */
    public $phone;
    /**
     * @Column(type="string", unique=true)
     */
    public $email;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $password = null;
    /**
     * @ManyToOne(targetEntity="Media")
     * @JoinColumn(name="photo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $photo;
    /**
     * @Column(type="smallint", options={"default" : 0})
     */
    protected $state = 0;
    /**
     * @Column(type="string", nullable=true, unique=true)
     */
    protected $token = null;
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $token_time = null;
    /**
     * @Column(type="json_array", nullable=true)
     */
    protected $data;
    /**
     * @Column(type="datetime")
     */
    public $registered_at;
    /**
     * @Column(type="datetime")
     */
    public $updated_at;


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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return Media
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getTokenTime()
    {
        return $this->token_time;
    }

    /**
     * @param mixed $token_time
     */
    public function setTokenTime($token_time)
    {
        $this->token_time = $token_time;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAt()
    {
        return $this->registered_at;
    }

    /**
     * @param mixed $registered_at
     */
    public function setRegisteredAt(\DateTime $registered_at)
    {
        $this->registered_at = $registered_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @PrePersist
     */
    public function onPrePersist(){
        $this->setRegisteredAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
    }
    /**
     * @PreUpdate
     */
    public function onPreUpdate(){
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @PreRemove
     */
    public function onPreRemove()
    {
        $this->tmp_id = $this->getId();
    }

    /**
     * @PostRemove
     */
    public function onPostRemove()
    {
        if (!is_null($this->tmp_id) && is_dir(ROOT. '/public/upload/accounts/' . $this->tmp_id)) {
            delTree(ROOT . '/public/upload/accounts/' . $this->tmp_id);
            $this->tmp_id = null;
        }
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
            'last_name' => $this->getLastName(),
            'first_name' => $this->getFirstName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'photo' => $this->getPhoto(),
            'state' => $this->getState(),
            'data' => $this->getData(),
            'registered_at' => $this->getRegisteredAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}
