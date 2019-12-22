<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReplyRepository")
 **/
class Reply
{
    /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue
    */
    protected $rid;

    /**
      * @ORM\Column(type="string", length=200, nullable=false)
    */
    protected $r_content;

    /**
      * @ORM\Column(type="string", length=40, nullable=false)
    */
    protected $r_name;

    /**
      * @ORM\Column(type="integer", nullable=false)
    */
    protected $uid;

    /**
      * @ORM\Column(type="datetime")
    */
    protected $r_date;

    public function getId()
    {
        return $this->rid;
    }

    public function getContent()
    {
        return $this->r_content;
    }

    public function getName()
    {
        return $this->r_name;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getDate()
    {
        return $this->r_date;
    }

    public function setContent($content)
    {
        return $this->r_content = $content;
    }

    public function setName($name)
    {
        $this->r_name = $name;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }
}