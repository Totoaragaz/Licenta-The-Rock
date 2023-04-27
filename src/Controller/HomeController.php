<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{


    public function __construct(
        private Environment $twig,
        protected UserManager $userManager
    )
    {
    }



    #[Route(path: '', name: 'home')]
    public function renderHomeScreen(Request $request): Response
    {
        $darkMode = $request->query->get('darkMode');
        return new Response($this->twig->render('home.html.twig',
            ['darkMode' => $darkMode]
        ));
    }

    #[Route(path: 'home/setMode', name: 'home/setMode')]
    public function setMode(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->redirectToRoute('home', ['darkMode' => $this->userManager->getUserMode($user->getId())]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
    }
}
