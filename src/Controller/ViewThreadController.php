<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Manager\CommentManager;
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
        protected ThreadManager $threadManager,
        protected CommentManager $commentManager
    )
    {
    }

    #[Route(path: '/thread/{threadId}', name: 'viewThread')]
    public function viewThread(Request $request, string $threadId): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $thread = $this->threadManager->getThreadDtoById($threadId);

        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($user);
            if ($this->commentManager->submitComment($comment, $threadId)) {
                return $this->redirectToRoute('viewThread', ['threadId' => $threadId]);
            }
        }

        return new Response($this->twig->render('viewThread.html.twig', [
            'user' => $user,
            'thread' => $thread,
            'commentForm' => $form->createView(),
        ]));
    }

    #[Route(path: '/deleteComment/{commentId}', name: 'deleteComment')]
    public function deleteComment(string $commentId):void
    {
        $this->commentManager->deleteComment($commentId);
    }
}