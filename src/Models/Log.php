<?php

namespace Jet\Models;

use JetFire\Db\Model;
use Doctrine\ORM\Mapping;

/**
 * Class Log
 * @package Jet\Models
 * @Entity(repositoryClass="Jet\Models\LogRepository")
 * @Table(name="logs")
 * @HasLifecycleCallbacks
 */
class Log extends Model implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=100)
     */
    protected $channel;
    /**
     * @Column(type="string", length=32)
     */
    protected $level_name;
    /**
     * @Column(type="integer")
     */
    protected $level;
    /**
     * @Column(type="text")
     */
    protected $message;
    /**
     * @Column(type="datetime")
     */
    public $date;
    /**
     * @ManyToOne(targetEntity="Account")
     * @JoinColumn(name="account_id", nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    protected $account = null;

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
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getLevelName()
    {
        return $this->level_name;
    }

    /**
     * @param mixed $level_name
     */
    public function setLevelName($level_name)
    {
        $this->level_name = $level_name;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
     * @PrePersist
     */
    public function onPrePersist(){
        $this->setDate(new \DateTime('now'));
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
            'channel' => $this->getChannel(),
            'level_name' => $this->getLevelName(),
            'level' => $this->getLevel(),
            'message' => $this->getMessage(),
            'date' => $this->getDate(),
            'account' => $this->getAccount()
        ];
    }
}
