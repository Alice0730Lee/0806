<?php
namespace AppBundle\Tests\Command;

use AppBundle\Command\SyncSQLCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Entity\Bank;
use AppBundle\Entity\User;

class SyncSQLCommandTest extends KernelTestCase
{
    public function testEmpty()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('syncSQL');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();

        $this->assertContains('empty', $output);
    }

    public function testExcut()
    {
        $kernel = self::bootKernel();
        $redis = self::$kernel->getContainer()->get('snc_redis.default');
        $manager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        $user->setAccount('test');
        $manager->persist($user);
        $manager->flush();

        $bank = new Bank($user, 0, 0);
        $date = new \DateTime('now');

        $jsonData = [
            'user_id' => $bank->getUser()->getId(),
            'user_account' => $bank->getUser()->getAccount(),
            'cash' => 100,
            'total' => 100,
            'date' => strtotime($date->format('Y-m-d H:i:s'))
        ];
        $redis->lpush('bank', json_encode($jsonData));

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('syncSQL');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $data = $manager->find('AppBundle\Entity\bank', 6);
        $bankData = [
            'user_id' => $data->getUser()->getId(),
            'user_account' => $data->getUser()->getAccount(),
            'cash' => $data->getCash(),
            'total' => $data->getTotal(),
            'date' => strtotime($data->getDate()->format('Y-m-d H:i:s'))
        ];

        $dataKey = ['user_id', 'user_account', 'cash', 'total', 'date'];

        for ($i = 0; $i < count($dataKey); $i++) {
            $this->assertEquals($bankData[$dataKey[$i]], $jsonData[$dataKey[$i]], '', 5);
        }
    }
}
