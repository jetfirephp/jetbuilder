<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Website
 * @package Jet\Models
 * @Entity()
 * @Table(name="website_account_status")
 * @HasLifecycleCallbacks
 */
class WebsiteAccountStatus extends Model implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $website;
    /**
     * @ManyToOne(targetEntity="Account", inversedBy="websites")
     * @JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $account;
    /**
     * @ManyToOne(targetEntity="WebsiteStatus")
     * @JoinColumn(name="website_status_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $status;
    /**
     * @Column(type="datetime")
     */
    public $created_at;
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
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }
    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param WebsiteStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
  
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @PrePersist
     */
    public function onPrePersist(){
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
    }
    /**
     * @PreUpdate
     */
    public function onPreUpdate(){
        $this->setUpdatedAt(new \DateTime('now'));
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
            'website' => $this->getWebsite(),
            'account' => $this->getAccount(),
            'status' => $this->getStatus(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}