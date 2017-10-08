<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Route
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\RouteRepository")
 * @Table(name="routes")
 */
class Route extends Model implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string",length=150)
     */
    protected $url;
    /**
     * @Column(type="string",length=100)
     */
    protected $name;
    /**
     * @Column(type="json")
     */
    protected $method = ["GET"];
    /**
     * @Column(type="json", nullable=true)
     */
    protected $arguments;
    /**
     * @Column(type="json", nullable=true)
     */
    protected $middlewares;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $subdomain;
    /**
     * @ManyToOne(targetEntity="Website")
     * @JoinColumn(name="website_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $website;
    /**
     * @Column(type="integer")
     */
    protected $position = 0;

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
     * @return array
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return mixed
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param mixed $middlewares
     */
    public function setMiddlewares($middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @param mixed $middleware
     */
    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }


    /**
     * @return mixed
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param mixed $subdomain
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * @return Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'method' => $this->getMethod(),
            'arguments' => $this->getArguments(),
            'middlewares' => $this->getMiddlewares(),
            'subdomain' => $this->getSubdomain(),
            'position' => $this->getPosition(),
            'website' => (is_null($this->getWebsite())) ? null : $this->getWebsite()->getId()
        ];
    }
}