<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="cashIn", type="decimal", precision=60, scale=4, nullable=false, options={"default" : 0})
     */
    private $cashIn = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="cashOut", type="decimal", precision=60, scale=4, nullable=false, options={"default" : 0})
     */
    private $cashOut = 0;

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

    public function setCashIn($cashIn)
    {
        $this->cashIn = $cashIn;

        return $this;
    }

    public function getCashIn()
    {
        return $this->cashIn;
    }

    public function setCashOut($cashOut)
    {
        $this->cashOut = $cashOut;

        return $this;
    }

    public function getCashOut()
    {
        return $this->cashOut;
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
