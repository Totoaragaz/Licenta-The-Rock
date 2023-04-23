<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class HomeController
{
    public function __construct(private Environment $twig)
    {
    }

    #[Route(path: '', name: 'home')]
    public function renderLogInScreen(): Response
    {
        return new Response($this->twig->render('home.html.twig'));
    }
    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
    }
}
