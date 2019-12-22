<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\Bank;

class BankTest extends \PHPUnit\Framework\TestCase
{
    public function testSetUser()
    {
        $user = new User();
        $bank = new Bank($user, 0, 0);
        $bank->setUser($user);

        $this->assertEquals($user, $bank->getUser());
        $this->assertTrue($bank->getId() == NULL);
    }

    public function testCash()
    {
        $user = new User();
        $bankAdd = new Bank($user, 100, 0);
        $bankAdd->setCash(100);
        $bankAdd->setTotal(100);

        $this->assertEquals('100', $bankAdd->getCash());

        $bankCut = new Bank($user, -100, 100);

        $this->assertEquals('-100', $bankCut->getCash());
        $this->assertEquals('100', $bankCut->getTotal());
    }

    public function testSetDate()
    {
        $user = new User();
        $bank = new Bank($user, 0, 0);
        $date = new \DateTime('now');
        $bank->setDate($date);

        $this->assertEquals($date, $bank->getDate());
    }
}
