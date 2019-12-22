<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdd = new User();
        $userAdd->setAccount('add');

        $userCut = new User();
        $userCut->setAccount('cut');

        $manager->persist($userAdd);
        $manager->persist($userCut);
        $manager->flush();

        $this->addReference('userAdd', $userAdd);
        $this->addReference('userCut', $userCut);
    }

    public function getOrder()
    {
        return 1;
    }
}
