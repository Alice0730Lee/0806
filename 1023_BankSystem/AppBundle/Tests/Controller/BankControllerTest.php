<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Bank;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\DataFixtures\ORM\LoadCashData;
use AppBundle\DataFixtures\ORM\LoadUserData;

class BankControllerTest extends WebTestCase
{
    protected $client;
    protected $doCash;
    protected $container;
    protected $em;
    protected $redis;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->redis = $this->container->get('snc_redis.default');

        $this->doCash = $this->loadFixtures(
            [
            LoadUserData::class,
            LoadCashData::class
            ]);
    }

    public function testChooseSearch()
    {
        $crawler = $this->client->request(
            'PUT',
            '/choose'
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $crawler = $this->client->request(
            'PUT',
            '/choose',
            [
                'id' => '1',
                'isSearch' => true
            ]
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testChooseCashAdd()
    {
        $crawler = $this->client->request(
            'PUT',
            '/choose',
            [
                'id' => '1',
                'isIn' => true
            ]
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testChooseCashCut()
    {
        $crawler = $this->client->request(
            'PUT',
            '/choose',
            [
                'id' => '1',
                'isOut' => true
            ]
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testDoCashAdd()
    {
        $crawler = $this->client->request(
            'POST',
            '/doCash'
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $crawler = $this->client->request(
            'POST',
            '/doCash',
            [
                'id' => '1',
                'cash' => 'aaa',
                'isIn' => true
            ]
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $cashAdd = $this->em->find('AppBundle\Entity\Bank', 1);

        $jsonData = [
            'user_id' => $cashAdd->getUser()->getId(),
            'user_account' => $cashAdd->getUser()->getAccount(),
            'cash' => $cashAdd->getCash(),
            'total' => $cashAdd->getTotal(),
            'date' => strtotime($cashAdd->getDate()->format('Y-m-d H:i:s'))
        ];

        $crawler = $this->client->request(
            'POST',
            '/doCash',
            [
                'id' => '1',
                'cash' => '300',
                'isIn' => true
            ]
        );

        $testData = json_decode($this->client->getResponse()->getContent(), TRUE);
        $testData['date'] = strtotime($testData['date']);
        $dataKey = ['user_id', 'user_account', 'cash', 'total', 'date'];

        for ($i = 0; $i < count($dataKey); $i++) {
            $this->assertEquals($testData[$dataKey[$i]], $jsonData[$dataKey[$i]], '', 5);
        }
    }

    public function testDocashCut()
    {
        $crawler = $this->client->request(
            'POST',
            '/doCash',
            [
                'id' => '1',
                'cash' => '1000000',
                'isOut' => true
            ]
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $cashCut = $this->em->find('AppBundle\Entity\Bank', 4);

        $jsonData = [
            'user_id' => $cashCut->getUser()->getId(),
            'user_account' => $cashCut->getUser()->getAccount(),
            'cash' => $cashCut->getCash(),
            'total' => $cashCut->getTotal(),
            'date' => strtotime($cashCut->getDate()->format('Y-m-d H:i:s'))
        ];

        $crawler = $this->client->request(
            'POST',
            '/doCash',
            [
                'id' => '2',
                'cash' => '300',
                'isOut' => true
            ]
        );

        $testData = json_decode($this->client->getResponse()->getContent(), TRUE);
        $testData['date'] = strtotime($testData['date']);
        $dataKey = ['user_id', 'user_account', 'cash', 'total', 'date'];

        for ($i = 0; $i < count($dataKey); $i++) {
            $this->assertEquals($testData[$dataKey[$i]], $jsonData[$dataKey[$i]], '', 5);
        }
    }

    public function testVersionWrong()
    {
        $crawler = $this->client->request(
            'POST',
            '/doCash',
            [
                'id' => '1',
                'cash' => '1000000',
            ]
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    public function testSearch()
    {
        $crawler = $this->client->request(
            'GET',
            '/search'
        );
        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $page = 1;
        $user = $this->em->find('AppBundle\Entity\User', 1);

        $bankQuery = $this->em->createQueryBuilder()
            ->select('t')
            ->from('AppBundle\Entity\Bank', 't')
            ->where('t.user = :user')
            ->setParameter('user', $user);
        $banks = $this->em->getRepository(Bank::class)->page($bankQuery, $page);

        foreach ($banks['result'] as $bank) {
            $temp = [
                'cash' => (string)$bank->getCash(),
                'total' => (string)$bank->getTotal(),
                'date' => $bank->getDate()->format('Y-m-d H:i:s')
            ];

            $jsonData[] = $temp;
        }

        $crawler = $this->client->request(
            'GET',
            '/search',
            [
                'id' => '1'
            ]
        );
        $this->assertEquals(json_encode($jsonData), $this->client->getResponse()->getContent());

        $crawler = $this->client->request(
            'GET',
            '/search',
            [
                'id' => '1',
                'page' => '1'
            ]
        );
        $this->assertEquals(json_encode($jsonData), $this->client->getResponse()->getContent());
    }

    public function tearDown()
    {
        $this->doCash = null;
        $this->em->close();
        $this->em = null;
        $this->redis->flushall();
        $this->redis->quit();
    }
}
