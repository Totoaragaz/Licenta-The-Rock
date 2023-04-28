<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        //$userRepository = static::getContainer()->get(UserRepository::class);
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfg',
            'registration_form[email]' => 'asdfg@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
            );
        $client->submit($form,$data);
        self::assertResponseRedirects('/login?successfulRegistration=1');
        /*$user = $userRepository->getUserWithEmail('asdfg@asdf.com')->getId();
        var_dump($user);
        $userRepository->deleteUser($user);*/
    }

    public function testUsernametaken(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfg',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testUsernameTooShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'A',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testEmailtaken(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'asdfg@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testInvalidEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'a.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testPasswordInvalidComplexity(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'asdfasdf1',
            'registration_form[password][second]' => 'asdfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testPasswordTooShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'Aa1',
            'registration_form[password][second]' => 'Aa1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testPasswordsDontMatch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Adfasdf1',
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }

    public function testBioTooLong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();
        $data = array(
            'registration_form[username]' => 'Asdfgh',
            'registration_form[email]' => 'asdfgh@asdf.com',
            'registration_form[password][first]' => 'Asdfasdf1',
            'registration_form[password][second]' => 'Asdfasdf1',
            'registration_form[bio]' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'
        );
        $client->submit($form,$data);
        self::assertPageTitleContains('Register');
    }
}
