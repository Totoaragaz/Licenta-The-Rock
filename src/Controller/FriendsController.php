<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FriendsController
{
    public function __construct(
        private Environment $twig,
    )
    {
    }

    #[Route(path: '/friends', name: 'friends')]
    public function renderHomeScreen(Request $request): Response
    {
        $darkMode = $request->query->get('darkMode');
        return new Response($this->twig->render('friends.html.twig',
            ['darkMode' => $darkMode]
        ));
    }
}