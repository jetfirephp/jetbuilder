<?php

namespace Jet\Models;

use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Theme
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\ThemeRepository")
 * @Table(name="themes")
 * @HasLifecycleCallbacks
 */
class Theme extends Model implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", unique=true, length=32)
     */
    protected $name;
    /**
     * @Column(type="string", unique=true)
     */
    protected $directory;
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
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }
    

    /**
     * @return Media
     */
    public function getThumbnail()
    {
        return ROOT . '/src/Themes/' . $this->getDirectory() . '/thumbnail.jpg';
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
            'name' => $this->getName(),
            'directory' => $this->getDirectory(),
            'thumbnail' => $this->getThumbnail(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}