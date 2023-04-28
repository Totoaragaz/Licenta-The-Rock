<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form();
        $data = array('_username' => 'asdf','_password' => 'Asdfasdf1');
        $client->submit($form,$data);
        self::assertResponseRedirects('http://localhost/home/setMode');
    }

    public function testInvalidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form();
        $data = array('_username' => 'asdf','_password' => 'wrongPassword');
        $client->submit($form,$data);
        self::assertResponseRedirects('http://localhost/login');
    }

    public function testUnverifiedLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form();
        $data = array('_username' => 'asdf2','_password' => 'Asdfasdf1');
        $client->submit($form,$data);
        self::assertResponseRedirects('http://localhost/login');
    }

}
