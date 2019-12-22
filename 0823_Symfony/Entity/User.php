<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

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
      * @ORM\Column(type="string", columnDefinition="varchar(200) not null")
    */
    protected $content;

    /**
      * @ORM\Column(type="string", columnDefinition="varchar(40) not null")
    */
    protected $name;

    /**
      * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
    */
    protected $date;

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

    public function setContent($content)
    {
        return $this->content = $content;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
