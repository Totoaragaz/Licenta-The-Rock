<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Manager\UserManager;
use App\Service\Implementations\UploadPictureServiceImpl;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class RegisterController extends AbstractController
{
    public function __construct(
        private Environment              $twig,
        private UploadPictureServiceImpl $uploadPictureServiceImpl,
        private UserManager              $userManager
    )
    {
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $user->setRegistrationDate(date_create());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('profilePicture')->getData();

            if ($picture) {
                $path = $this->uploadPictureServiceImpl->uploadPicture($picture);
                $user->setImage($path);
            }

            if ($this->userManager->saveUser($user)) {

                return $this->redirectToRoute('login', ['successfulRegistration' => true]);
            }
        }

        return new Response(
            $this->twig->render('register.html.twig', [
                'registrationForm' => $form->createView()
            ])
        );
    }

    #[Route(path: '/register/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, MailService $mailService): Response
    {
        return $this->redirectToRoute($mailService->getConfirmationRedirectRoute($request), ['emailVerified' => true]);
    }
}
