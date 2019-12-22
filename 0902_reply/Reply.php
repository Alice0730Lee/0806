<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

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
      * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="replys")
      * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getDate()
    {
        return $this->r_date;
    }

    public function __construct()
    {
        $this->r_date = new DateTime("now");
    }

    public function setContent($content)
    {
        return $this->r_content = $content;
    }

    public function setName($name)
    {
        $this->r_name = $name;
    }

    public function setDate($date)
    {
        $this->r_date = $date;
    }
    
    public function setUser(?User $user):self
    {
        $this->user = $user;

        return $this;
    }
}