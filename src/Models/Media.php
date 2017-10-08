<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Media
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\MediaRepository")
 * @Table(name="medias")
 * @HasLifecycleCallbacks
 */
class Media extends Model implements \JsonSerializable
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
     * @Column(type="string", unique=true)
     */
    protected $path;
    /**
     * @Column(type="string")
     */
    protected $type;
    /**
     * @Column(type="float")
     */
    protected $size;
    /**
     * @ManyToOne(targetEntity="Account")
     * @JoinColumn(name="account_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $account;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $alt;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $website;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
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
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param array $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
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
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTime
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
    public function onPrePersist()
    {
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @PostRemove
     */
    public function onPostRemove()
    {
        if (is_file($this->getFullPath()))
            unlink($this->getFullPath());
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return WEBROOT . ltrim($this->path, '/');
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return ROOT . $this->path;
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
            'type' => $this->getType(),
            'alt' => $this->getAlt(),
            'website' =>
                (!is_null($this->getWebsite()))
                    ? [
                    'id' => $this->getWebsite()->getId(),
                    'domain' => $this->getWebsite()->getDomain()
                ]
                    : null
            ,
            'size' => $this->getSize(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'path' => $this->getPath(),
            'public_path' => $this->getPublicPath()
        ];
    }
}