<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Entity\User;
use App\Form\EditThreadFormType;
use App\Manager\ThreadManager;
use App\Service\Implementations\UploadPictureServiceImpl;
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
        protected UploadPictureServiceImpl $uploadPictureServiceImpl,
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
        $form->handleRequest($request);

        $this->initializeImageSession($request, $thread);

        if ($form->isSubmitted() && $form->isValid()) {
            $originalContent = $thread->getContent();
            $images = $this->keepTemporaryImages($request);
            sleep(5);
            var_dump([$form->get('content')->getData(), $images, $originalContent]);

            return $this->redirectToRoute('viewThread', [
                'threadId' => $threadId,
            ]);
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

    private function initializeImageSession(Request $request, Thread $thread): void
    {
        $session = $request->getSession();
        $i = 0;
        foreach ($thread->getContent() as $contentBit) {
            if (file_exists('img/' . $contentBit)) {
                $session->set('image' . $i, $contentBit);
                $i++;
            }
        }
    }

    private function keepTemporaryImages(Request $request): array
    {
        $session = $request->getSession();
        $images = [];
        for ($i = 0; $i < 5; $i++) {
            $image = $session->get('image' . $i);
            if ($image != null) {
                $this->uploadPictureServiceImpl->keepTemporaryPicture($image);
                $images[] = $image;
                $session->remove('image' . $i);
            }
        }

        return $images;
    }
}