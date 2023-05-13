<?php

namespace App\Tests\Service;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class UserServiceTest extends KernelTestCase
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

    public function getFriendState(User $user, string $friendUsername): string
    {
        $friend = $this->entityManager->getRepository(User::class)->findOneBy(['username'=> $friendUsername]);
        if ($user->getFriends()->contains($friend)) {
            return 'friends';
        } else if ($user->getIncomingFriendRequests()->contains($friend)) {
            return 'incomingRequest';
        } else if ($user->getOutgoingFriendRequests()->contains($friend)) {
            return 'outgoingRequest';
        } else {
            return 'none';
        }
    }

    public function testGetFriendStateFriends(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Totoaragaz']);
        $username = 'DemCoconuts';
        assertEquals($this->getFriendState($user, $username), 'friends');
    }

    public function testGetFriendStateIncomingRequest(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Totoaragaz']);
        $username = 'roro';
        assertEquals($this->getFriendState($user, $username), 'incomingRequest');
    }

    public function testGetFriendStateOutgoingRequest(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Totoaragaz']);
        $username = 'coco';
        assertEquals($this->getFriendState($user, $username), 'outgoingRequest');
    }

    public function testGetFriendStateNone(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Totoaragaz']);
        $username = 'bobo';
        assertEquals($this->getFriendState($user, $username), 'none');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}