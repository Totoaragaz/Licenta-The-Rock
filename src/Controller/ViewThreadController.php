<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\ThreadManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ViewThreadController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        protected ThreadManager $threadManager
    )
    {
    }

    #[Route(path: '/thread/{threadId}', name: 'viewThread')]
    public function renderForumScreen(Request $request, string $threadId): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $thread = $this->threadManager->getThreadById($threadId);

        return new Response($this->twig->render('viewThread.html.twig', [
            'user' => $user,
            'thread' => $thread
        ]));
    }
}