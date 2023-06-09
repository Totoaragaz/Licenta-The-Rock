<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Thread;
use App\Entity\User;
use App\Form\CreateThreadFormType;
use App\Manager\TagManager;
use App\Manager\ThreadManager;
use App\Service\Implementations\UploadPictureServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CreateThreadController extends AbstractController
{
    public function __construct(
        private Environment              $twig,
        private ThreadManager            $threadManager,
        private TagManager               $tagManager,
        private UploadPictureServiceImpl $uploadPictureServiceImpl,
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
            sleep(5);
            $images = $this->keepTemporaryImages($request);
            $thread->setAuthor($user);
            $thread->setTags($this->tagManager->createTags($form->get('tags')->getData()));

            if ($this->threadManager->createThread($thread)) {
                $this->threadManager->setThreadContent($thread, $form->get('content')->getData(), $images);
                $this->tagManager->saveTags($thread);

                return $this->redirectToRoute('viewThread', [
                    'threadId' => $thread->getId()]);
            }
        }


        return new Response($this->twig->render('createThread.html.twig', [
            'user' => $user,
            'createThreadForm' => $form->createView()
        ]));
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

    #[Route(path: '/uploadImage', name: 'uploadImage')]
    public function addTemporaryImage(Request $request): Response
    {
        $session = $request->getSession();
        $number = $request->get('number');
        $image = $request->files->get('image' . $number);
        if ($image != null) {
            $session->set('image' . $number, $this->uploadPictureServiceImpl->uploadTemporaryPicture($image));
        }

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/removeAllImages', name: 'removeAllImages')]
    public function removeAllTemporaryImages(Request $request): Response
    {
        sleep(30);
        $session = $request->getSession();
        for ($i = 0; $i < 5; $i++) {
            $image = $session->get('image' . $i);
            if ($image != null) {
                $this->uploadPictureServiceImpl->deleteTemporaryPicture($image);
                $session->remove('image' . $i);
            }
        }

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/removeImage', name: 'removeImage')]
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

        return $this->json($session, Response::HTTP_OK);
    }
}
