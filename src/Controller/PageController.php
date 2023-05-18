<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route(path: '/setSession', name: 'setSession')]
    public function setSession(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $request->getSession();
        $session->set('darkMode', $user->getDarkMode());
        $session->set('mainColumn', $user->getMainColumn());
        $session->set('chatColumn', $user->getChatColumn());
        $session->set('friendColumn', $user->getFriendColumn());
        $session->set('incomingRequests', true);
        $session->set('outgoingRequests', true);
        $session->set('friends', true);

        return $this->redirectToRoute('forum');
    }

    #[Route(path: '/setMainColumn', name: 'setMainColumn')]
    public function setMainColumn(Request $request): void
    {
        $session = $request->getSession();
        $session->set('mainColumn', !$session->get('mainColumn'));
    }

    #[Route(path: '/setFriendColumn', name: 'setFriendColumn')]
    public function setFriendColumn(Request $request): void
    {
        $session = $request->getSession();
        $session->set('friendColumn', !$session->get('friendColumn'));
    }

    #[Route(path: '/setChatColumn', name: 'setChatColumn')]
    public function setChatColumn(Request $request): void
    {
        $session = $request->getSession();
        $session->set('chatColumn', !$session->get('chatColumn'));
    }

    #[Route(path: '/setIncomingRequests', name: 'setIncomingRequests')]
    public function setIncomingRequests(Request $request): void
    {
        $session = $request->getSession();
        $session->set('incomingRequests', !$session->get('incomingRequests'));
    }

    #[Route(path: '/setOutgoingRequests', name: 'setOutgoingRequests')]
    public function setOutgoingRequests(Request $request): void
    {
        $session = $request->getSession();
        $session->set('outgoingRequests', !$session->get('outgoingRequests'));
    }

    #[Route(path: '/setFriends', name: 'setFriends')]
    public function setFriends(Request $request): void
    {
        $session = $request->getSession();
        $session->set('friends', !$session->get('friends'));
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        session_destroy();
    }
}