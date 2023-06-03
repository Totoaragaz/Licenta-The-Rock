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

        $this->initializeImageSession($request, $thread);

        $form = $this->createForm(EditThreadFormType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            $images = $this->keepTemporaryImages($request);
            sleep(5);

            $this->threadManager->editThreadContent($thread, $form->get('content')->getData(), $images);

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
            if ($contentBit->getType() == 'image') {
                $session->set('image' . $i, $contentBit->getContent());
                $i++;
            }
        }
    }

    #[Route(path: '/removeImageEdit', name: 'removeImageEdit')]
    public function removeTemporaryImage(Request $request): Response
    {
        $session = $request->getSession();
        $number = $request->get('number');
        $image = $session->get('image' . $number);
        if ($image != null) {
            if (!file_exists('img/' . $image)) {
                $this->uploadPictureServiceImpl->deleteTemporaryPicture($image);
            }
        }
        $session->remove('image' . $number);

        return $this->json($session,Response::HTTP_OK);
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