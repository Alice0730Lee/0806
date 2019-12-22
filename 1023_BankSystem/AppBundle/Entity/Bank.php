<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

/**
 * Bank
 *
 * @ORM\Table(name="bank")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BankRepository")
 */
class Bank
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="banks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="cash", type="decimal", precision=60, scale=4, nullable=false, options={"default" : 0})
     */
    private $cash = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=60, scale=4, nullable=false, options={"default" : 0})
     */
    private $total = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct($user, $cash, $total)
    {
        $this->user = $user;
        $this->date = new \DateTime('now');
        $this->cash = $cash;
        $this->total = $total;

        $user->setTotal($total);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCash($cash)
    {
        $this->cash = $cash;

        return $this;
    }

    public function getCash()
    {
        return $this->cash;
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

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }
}
