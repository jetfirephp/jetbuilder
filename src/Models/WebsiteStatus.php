<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;


/**
 * Class WebsiteStatus
 * @package Jet\Models
 * @Entity()
 * @Table(name="website_status")
 */
class WebsiteStatus extends Model implements \JsonSerializable
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
    protected $role;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $website;

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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
    public function setWebsite($website)
    {
        $this->website = $website;
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
            'role' => $this->getRole(),
            'website' => $this->getWebsite()
        ];
    }
}
