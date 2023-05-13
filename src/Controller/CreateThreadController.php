<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CreateThreadController extends AbstractController
{
    public function __construct(
        private Environment $twig,
    ){

    }

    #[Route(path: '/createThread', name: 'createThread')]
    public function createThread(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return new Response($this->twig->render('createThread.html.twig',[
            'user' => $user,
        ]));
    }
}