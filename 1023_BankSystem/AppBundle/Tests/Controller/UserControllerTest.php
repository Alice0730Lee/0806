<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/show');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testDoJoin()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'isJoin' => true,
                'account' => '3'
            ]
        );
        $this->assertContains('申請成功，請再次輸入代號', $crawler->text());

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'isJoin' => true,
                'account' => '3'
            ]
        );
        $this->assertContains('代號已被申請，請重新輸入', $crawler->text());
    }

    public function testDoLogIn()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'account' => '3'
            ]
        );
        $this->assertFalse($client->getResponse()->isSuccessful());

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'isLogIn' => true
            ]
        );
        $this->assertContains('代號不得為空值', $crawler->text());

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'isLogIn' => true,
                'account' => '55'
            ]
        );
        $this->assertContains('查無此代號，請重新輸入', $crawler->text());

        $crawler = $client->request(
            'POST',
            '/do',
            [
                'isLogIn' => true,
                'account' => '3'
            ]
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
