<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Bank;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SyncSQLCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('syncSQL');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $redis = $this->getContainer()->get('snc_redis.default');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $len = $redis->llen('bank');

        if ($len > 0) {
            for ($i = $len; $i > 0; $i--) {
                $data = json_decode($redis->rpop('bank'));
                $user = $em->find('AppBundle\Entity\User', $data->user_id);
                $cash = $data->cash;
                $total = $data->total;

                $bank = new Bank($user, $cash, $total);

                $em->persist($bank);
                $em->flush();
            }

            $output->writeln(json_encode($data));
        } else {
            $output->writeln('empty');
        }
    }
}
