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
    public function renderForumScreen(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return new Response($this->twig->render('forum.html.twig', [
            'user' => $user,
            'mainColumn' => $request->query->get('mainColumn'),
            'chatColumn' => $request->query->get('chatColumn'),
            'friendColumn' => $request->query->get('friendColumn'),

        ]));
    }

    /*
    #[Route(path: 'home/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, string $item = ""): Response
    {
        $darkMode = $request->query->get('darkMode');
        return new Response($this->twig->render('forum.html.twig', []));
    }*/

    #[Route(path: '/forum/setMode', name: 'forum/setMode')]
    public function setMode(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->redirectToRoute('forum', [
            'darkMode' => $this->userManager->getUserMode($user->getId()),
            'mainColumn' => $this->userManager->getMainColumn($user->getId()),
            'chatColumn' => $this->userManager->getChatColumn($user->getId()),
            'friendColumn' => $this->userManager->getFriendColumn($user->getId())
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
    }
}
