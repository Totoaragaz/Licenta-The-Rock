<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FriendsController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private UserManager $userManager
    )
    {
    }

    #[Route(path: '/removeFriend/{username}', name: 'removeFriend')]
    public function removeFriend(string $username): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userManager->removeFriend($user, $username);
    }

    #[Route(path: '/acceptFriendRequest/{username}', name: 'acceptFriendRequest')]
    public function acceptFriendRequest(string $username): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userManager->acceptFriendRequest($user, $username);
    }

    #[Route(path: '/declineFriendRequest/{username}', name: 'declineFriendRequest')]
    public function declineFriendRequest(string $username): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userManager->declineFriendRequest($user, $username);
    }

    #[Route(path: '/sendFriendRequest/{username}', name: 'sendFriendRequest')]
    public function sendFriendRequest(string $username): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userManager->sendFriendRequest($user, $username);
    }

    #[Route(path: '/revokeFriendRequest/{username}', name: 'revokeFriendRequest')]
    public function revokeFriendRequest(string $username): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userManager->revokeFriendRequest($user, $username);
    }
}