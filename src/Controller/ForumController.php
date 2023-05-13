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
        $currentPage = $request->query->get('page', 1);
        $select = $request->query->get('select', 'threads');

        $numberOfPages = $this->userManager->getAllOtherUsersNumberOfPages($user->getUsername());

        $users = $this->userManager->getAllOtherUsers($user, $currentPage - 0);

        return new Response($this->twig->render('forum.html.twig', [
            'user' => $user,
            'users' => $users,
            'currentPage' => $currentPage,
            'numberOfPages' => $numberOfPages,
            'select' => $select,
            'query' => ' ',
        ]));
    }

    #[Route(path: '/search/{query}', name: 'search', methods: ['GET'])]
    public function search(Request $request, string $query): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $currentPage = $request->query->get('page', 1);
        $select = $request->query->get('select', 'threads');

        if ($query == ' ') {

            $numberOfPages = $this->userManager->getAllOtherUsersNumberOfPages($user->getUsername());
            $users = $this->userManager->getAllOtherUsers($user, $currentPage - 0);

            return new Response($this->twig->render('forum.html.twig', [
                'user' => $user,
                'users' => $users,
                'currentPage' => $currentPage,
                'numberOfPages' => $numberOfPages,
                'select' => $select,
                'query' => ' '
            ]));
        } else {

            $numberOfPages = $this->userManager->getSearchedUsersNumberOfPages($user->getUsername(), strtolower($query));
            $users = $this->userManager->getSearchedUsers($user, $query , $currentPage - 0);

            return new Response($this->twig->render('forum.html.twig', [
                'user' => $user,
                'users' => $users,
                'currentPage' => $currentPage,
                'numberOfPages' => $numberOfPages,
                'select' => $select,
                'query' => $query,
            ]));
        }
    }
}
