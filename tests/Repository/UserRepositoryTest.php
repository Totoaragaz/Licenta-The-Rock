<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindUserByUsername()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('Totoaragaz');
        $user->setImage('Regular-Balu-6453bd4fc903f.jpg');
        $user->setBio('Imi place papagal');
        $user->setEmail('totovisan7@yahoo.com');
        $user->setRole('ROLE_USER');
        $user->setRegistrationDate(date_create('05/04/2023'));
        $user->setMainColumn(1);
        $user->setChatColumn(1);
        $user->setDarkMode(0);
        $user->setFriendColumn(1);
        $user->setChatWarning(1);
        $user->setVerified(1);
        $user->setPassword('$2y$13$G3xb2lZI4dxoCe2qC4nvfeFKGdB2CsFg830.4aYdgQNXeIpxt/yVu');

        $foundUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Totoaragaz']);

        self::assertEquals($user->getId(), $foundUser->getId());
        self::assertEquals($user->getUsername(), $foundUser->getUsername());
        self::assertEquals($user->getPassword(), $foundUser->getPassword());
        self::assertEquals($user->getEmail(), $foundUser->getEmail());
        self::assertEquals($user->getFriends(), $foundUser->getFriends());
        self::assertEquals($user->getBio(), $foundUser->getBio());
        self::assertEquals($user->getImage(), $foundUser->getImage());
        self::assertEquals($user->getChatColumn(), $foundUser->getChatColumn());
        self::assertEquals($user->getChatWarning(), $foundUser->getChatWarning());
        self::assertEquals($user->getMainColumn(), $foundUser->getMainColumn());
        self::assertEquals($user->getDarkMode(), $foundUser->getDarkMode());
        self::assertEquals($user->getFriendColumn(), $foundUser->getFriendColumn());
        self::assertEquals($user->isVerified(), $foundUser->isVerified());
        self::assertEquals($user->getRole(), $foundUser->getRole());
        self::assertEquals($user->getRegistrationDate(),$foundUser->getRegistrationDate());
        //self::assertEquals($user->getFriends(), $foundUser->getFriends()); collectionurile distrug testele chiar daca sunt goale?
        //self::assertEquals($user->getThreads(), $foundUser->getThreads());
        //self::assertEquals($user->getOutgoingFriendRequests(), $foundUser->getOutgoingFriendRequests());
        //self::assertEquals($user->getIncomingFriendRequests(), $foundUser->getIncomingFriendRequests());
        //self::assertEquals($user->getComments(), $foundUser->getComments());
        //self::assertEquals($user, $foundUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}