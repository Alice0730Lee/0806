<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\User;

class UserTest extends \PHPUnit\Framework\TestCase
{
    public function testSetAccount()
    {
        $user = new User();
        $user->setAccount('test');

        $this->assertEquals('test', $user->getAccount());
    }

    public function testSetTotal()
    {
        $user = new User();
        $user->setTotal('0');

        $this->assertEquals(0, $user->getAccount());
    }
}
