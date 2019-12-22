<?php
/**
 * @Entity @Table(name="user")
 **/
class User
{
    /**
      * @Id
      * @Column(type="integer")
      * @GeneratedValue
    */
    protected $id;

    /**
      * @Column(type="string", columnDefinition="varchar(200) not null")
    */
    protected $content;

    /**
      * @Column(type="string", columnDefinition="varchar(40) not null")
    */
    protected $name;

    /**
      * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
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

    public function show()
    {
        $repository = $this->getDoctrine()->getRepository('User::class');
        $user = $repository->findAll();
    }
}