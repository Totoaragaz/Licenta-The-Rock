<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\ThreadManager;
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
        protected UserManager $userManager,
        protected ThreadManager $threadManager
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

        $threads = $this->threadManager->getAllThreads($user->getUsername(), $currentPage - 0);
        $users = $this->userManager->getAllOtherUsers($user, $currentPage - 0);

        $usersNumberOfPages = $this->getNumberOfPages($users);
        $threadsNumberOfPages = $this->getNumberOfPages($threads);

        return new Response($this->twig->render('forum.html.twig', [
            'user' => $user,
            'users' => $users,
            'threads' => $threads,
            'currentPage' => $currentPage,
            'usersNumberOfPages' => $usersNumberOfPages,
            'threadsNumberOfPages' => $threadsNumberOfPages,
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

            $threads = $this->threadManager->getAllThreads($user->getUsername(), $currentPage - 0);
            $users = $this->userManager->getAllOtherUsers($user, $currentPage - 0);

            $usersNumberOfPages = $this->getNumberOfPages($users);
            $threadsNumberOfPages = $this->getNumberOfPages($threads);

            return new Response($this->twig->render('forum.html.twig', [
                'user' => $user,
                'users' => $users,
                'threads' => $threads,
                'currentPage' => $currentPage,
                'usersNumberOfPages' => $usersNumberOfPages,
                'threadsNumberOfPages' => $threadsNumberOfPages,
                'select' => $select,
                'query' => ' ',
            ]));
        } else {

            $users = $this->userManager->getSearchedUsers($user, $query , $currentPage - 0);
            $threads = $this->threadManager->getSearchedThreads($user->getUsername(), $query, $currentPage - 0);

            $usersNumberOfPages = $this->getNumberOfPages($users);
            $threadsNumberOfPages = $this->getNumberOfPages($threads);

            return new Response($this->twig->render('forum.html.twig', [
                'user' => $user,
                'users' => $users,
                'threads' => $threads,
                'threadsNumberOfPages' => $threadsNumberOfPages,
                'currentPage' => $currentPage,
                'usersNumberOfPages' => $usersNumberOfPages,
                'select' => $select,
                'query' => $query,
            ]));
        }
    }

    private function getNumberOfPages (array $array): int
    {
        return intdiv(sizeof($array), 10) - (sizeof($array) % 10 === 0) + 1;
    }
}
