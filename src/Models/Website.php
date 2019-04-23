<?php

namespace Jet\Models;

use Doctrine\Common\Collections\ArrayCollection;
use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Website
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\WebsiteRepository")
 * @Table(name="websites")
 * @HasLifecycleCallbacks
 */
class Website extends Model implements \JsonSerializable
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
     * @Column(type="string", unique=true)
     */
    protected $name;
    /**
     * @Column(type="string", unique=true)
     */
    protected $url;
    /**
     * @ManyToOne(targetEntity="Theme")
     * @JoinColumn(name="theme_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $theme;
    /**
     * @ManyToOne(targetEntity="Template")
     * @JoinColumn(name="layout_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $layout;
    /**
     * @ManyToOne(targetEntity="Language")
     * @JoinColumn(name="default_language_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $default_language;
    /**
     * @ManyToMany(targetEntity="Language")
     * @JoinTable(name="website_languages",
     *      joinColumns={@JoinColumn(name="website_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $languages;
    /**
     * @Column(type="smallint", options={"default" : -1})
     */
    protected $state = 1;
    /**
     * @Column(type="json_array", nullable=true)
     */
    protected $data;
    /**
     * @Column(type="datetime")
     */
    public $created_at;
    /**
     * @Column(type="datetime")
     */
    public $updated_at;

    /**
     * Website constructor.
     */
    public function __construct()
    {
        $this->languages = new ArrayCollection();
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param Theme $theme
     */
    public function setTheme(Theme $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return Template
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param Template $layout
     */
    public function setLayout(Template $layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        return $this->default_language;
    }

    /**
     * @param mixed $default_language
     */
    public function setDefaultLanguage($default_language)
    {
        $this->default_language = $default_language;
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @param Language $language
     */
    public function addLanguage(Language $language)
    {
        $this->languages[] = $language;
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
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function addData($data)
    {
        $this->data[] = $data;
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
        if (!is_null($this->tmp_id) && is_dir(ROOT . '/public/upload/default/sites/' . $this->tmp_id)) {
            delTree(ROOT . '/public/upload/default/sites/' . $this->tmp_id);
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
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'layout' => $this->getLayout(),
            'theme' => (is_null($this->getTheme())) ? null : $this->getTheme()->getId(),
            'default_language' => $this->getDefaultLanguage(),
            'state' => $this->getState(),
            'data' => $this->getData(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}