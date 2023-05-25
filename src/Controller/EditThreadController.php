<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditThreadFormType;
use App\Manager\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class EditThreadController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        protected ThreadManager $threadManager,
    )
    {
    }

    #[Route(path: '/editThread/{threadId}', name: 'editThread')]
    public function editThread(Request $request, string $threadId): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $thread = $this->threadManager->getThreadObjectById($threadId);

        $form = $this->createForm(EditThreadFormType::class, $thread);

        if ($form->isSubmitted() && $form->isValid()) {
            $thread->setClosed($form->get('close')->getData());
            var_dump($form->getData());

        }

        return new Response($this->twig->render('editThread.html.twig', [
            'user' => $user,
            'uploadDate' => $thread->getUploadDate()->format('d/m/Y h:i:s'),
            'thread' => $thread,
            'editThreadForm' => $form->createView()
        ]));
    }

    #[Route(path: '/deleteThread/{threadId}', name: 'deleteThread')]
    public function deleteThread(string $threadId): void
    {
        $this->threadManager->deleteThread($threadId);
    }
}