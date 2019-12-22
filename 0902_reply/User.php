<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 **/
class User
{
    /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue
    */
    protected $id;

    /**
      * @ORM\Column(type="string", length=200, nullable=false)
    */
    protected $content;

    /**
      * @ORM\Column(type="string", length=40, nullable=false)
    */
    protected $name;

    /**
      * @ORM\Column(type="datetime")
    */
    protected $date;

    /**
      * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="user")
    */
    protected $replys;

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDate()
    {
        return $this->date;
    }
    public function getReplys(): Collection
    {
        return $this->replys;
    }

    public function __construct()
    {
        $this->date = new DateTime("now");
        $this->replys = new ArrayCollection();
    }

    public function setContent($content)
    {
        return $this->content = $content;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
}
