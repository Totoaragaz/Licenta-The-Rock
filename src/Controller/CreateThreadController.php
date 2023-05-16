<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Entity\User;
use App\Form\CreateThreadFormType;
use App\Manager\TagManager;
use App\Manager\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CreateThreadController extends AbstractController
{
    public function __construct(
        private Environment   $twig,
        private ThreadManager $threadManager,
        private TagManager $tagManager
    )
    {

    }

    #[Route(path: '/createThread', name: 'createThread')]
    public function createThread(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $thread = new Thread();

        $form = $this->createForm(CreateThreadFormType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            var_dump($form->get('images')->getData());
            var_dump($thread->setContent($this->threadManager->setThreadContent($form->get('content')->getData(), $form->get('images')->getData())));
            $thread->setAuthor($user);
            $thread->setTags($this->tagManager->createTags($form->get('tags')->getData()));

            if ($this->threadManager->createThread($thread)) {
                $this->tagManager->saveTags($thread);
                return $this->redirectToRoute('forum');
            }
        }


        return new Response($this->twig->render('createThread.html.twig', [
            'user' => $user,
            'createThreadForm' => $form->createView()
        ]));
    }
}