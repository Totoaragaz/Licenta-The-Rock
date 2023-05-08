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

class ForumController extends AbstractController
{


    public function __construct(
        private Environment $twig,
        protected UserManager $userManager
    )
    {
    }

    #[Route(path: '', name: 'forum')]
    public function renderForumScreen(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return new Response($this->twig->render('forum.html.twig', [
            'user' => $user,
        ]));
    }

    #[Route(path: '/search/{items}', name: 'search', methods: ['GET'])]
    public function search(Request $request, string $items): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return new Response($this->twig->render('forum.html.twig', [
            'user' => $user,
        ]));
    }
}
