<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Bank;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

class LoadCashData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $redis = $this->container->get('snc_redis.default');

        $userAdd = $this->getReference('userAdd');
        $userCut = $this->getReference('userCut');
        $userAddId = $this->getReference('userAdd')->getId();
        $userCutId = $this->getReference('userCut')->getId();

        $cashAdd = 300;
        $cashCut = -300;
        $date = new \DateTime('now');
        $redis->hsetnx($userAddId, 'total', (int)$userAdd->getTotal());
        $redis->hsetnx($userCutId, 'total', (int)$userCut->getTotal());

        $redis->hincrby($userAddId, 'total', $cashAdd);
        $jsonData = [
            'user_id' => $userAddId,
            'user_account' => $userAdd->getAccount(),
            'cash' => $cashAdd,
            'total' => $redis->hget($userAddId, 'total'),
            'date' => $date->format('Y-m-d H:i:s')
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $redis->hincrby($userAddId, 'total', $cashCut);
        $jsonData = [
            'user_id' => $userAddId,
            'user_account' => $userAdd->getAccount(),
            'cash' => $cashCut,
            'total' => $redis->hget($userCutId, 'total'),
            'date' => $date->format('Y-m-d H:i:s')
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $redis->hincrby($userCutId, 'total', $cashAdd);
        $jsonData = [
            'user_id' => $userCutId,
            'user_account' => $userCut->getAccount(),
            'cash' => $cashAdd,
            'total' => $redis->hget($userCutId, 'total'),
            'date' => $date->format('Y-m-d H:i:s')
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $redis->hincrby($userCutId, 'total', $cashCut);
        $jsonData = [
            'user_id' => $userCutId,
            'user_account' => $userCut->getAccount(),
            'cash' => $cashCut,
            'total' => $redis->hget($userCutId, 'total'),
            'date' => $date->format('Y-m-d H:i:s')
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $redis->hincrby($userCutId, 'total', $cashAdd);
        $jsonData = [
            'user_id' => $userCutId,
            'user_account' => $userCut->getAccount(),
            'cash' => $cashAdd,
            'total' => $redis->hget($userCutId, 'total'),
            'date' => $date->format('Y-m-d H:i:s')
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $kernel = $this->container->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'syncSQL']);
        $application->run($input);
    }

    public function getOrder()
    {
        return 2;
    }
}
