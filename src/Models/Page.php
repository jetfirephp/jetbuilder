<?php

namespace Jet\Models;


use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Page
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\PageRepository")
 * @Table(name="pages")
 * @HasLifecycleCallbacks
 */
class Page extends Model implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @ManyToOne(targetEntity="Route")
     * @JoinColumn(name="route_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $route;
    /**
     * @Column(type="string")
     */
    protected $title;
    /**
     * @OneToMany(targetEntity="Content", mappedBy="page")
     */
    protected $contents;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $website;
    /**
     * @ManyToOne(targetEntity="Template")
     * @JoinColumn(name="layout_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $layout;
    /**
     * @Column(type="string", length=10)
     */
    protected $type = 'static';
    /**
     * @Column(type="boolean")
     */
    protected $builder = false;
    /**
     * @Column(type="boolean")
     */
    protected $published = true;
    /**
     * @Column(type="datetime")
     */
    public $created_at;
    /**
     * @Column(type="datetime")
     */
    public $updated_at;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->contents = new ArrayCollection();
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
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route)
    {
        $this->route = $route;
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
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @param Content $content
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;
    }

    /**
     * @return Template
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout(Template $layout)
    {
        $this->layout = $layout;
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
        if (in_array($type, ['static', 'dynamic'])) {
            $this->type = $type;
        }
    }

    /**
     * @param mixed $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return bool
     */
    public function isBuilder()
    {
        return $this->builder;
    }

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
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
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
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
            'layout' => $this->getLayout(),
            'route' => $this->getRoute(),
            'website' => [
                'id' => $this->getWebsite()->getId(),
                'domain' => $this->getWebsite()->getDomain(),
            ],
            'type' => $this->getType(),
            'builder' => $this->isBuilder(),
            'published' => $this->isPublished(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }

}