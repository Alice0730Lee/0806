<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="account", type="string", length=255, unique=true)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=60, scale=4, nullable=false, options={"default" : 0})
     */
    private $total = 0;

    /**
     * @ORM\OneToMany(targetEntity="Bank", mappedBy="user")
     */
    private $banks;

    /**
     * @ORM\Version
     * @ORM\Column(type="integer")
     */
    private $version;

    public function __construct()
    {
        $this->banks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }
}
