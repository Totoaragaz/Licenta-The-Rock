<?php

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
    public const REGISTER_SUCCESS = 'registerSuccess';
    public const REGISTER_CONFIRM = 'confirmationPage';

    private UploadPictureServiceImpl $uploadPictureServiceImpl;
    private UserManager $userManager;
    private Environment $twig;

    public function __construct(
        Environment $twig,
        UploadPictureServiceImpl $uploadPictureServiceImpl,
        UserManager $userManager
    ) {
        $this->twig = $twig;
        $this->uploadPictureServiceImpl = $uploadPictureServiceImpl;
        $this->userManager = $userManager;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('profilePicture')->getData();

            if ($picture) {
                $path = $this->uploadPictureServiceImpl->uploadPicture($picture);
                $user->setImage($path);
            }

            if ($this->userManager->saveUser($user)) {
                return $this->redirectToRoute(RegisterController::REGISTER_SUCCESS);
            }
        }

        return new Response(
            $this->twig->render('register.html.twig', [
                'registrationForm' => $form->createView(),
            ])
        );
    }

    #[Route(path: '/register/success', name: RegisterController::REGISTER_SUCCESS, methods: ['GET'])]
    public function renderSuccessfulRegistrationPage(): Response
    {
        return new Response($this->twig->render('successfulRegistration.html.twig'));
    }

    #[Route(path: '/register/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, MailService $mailService): Response
    {
        return $this->redirectToRoute($mailService->getConfirmationRedirectRoute($request));
    }

    #[Route(path: '/register/confirm', name: RegisterController::REGISTER_CONFIRM)]
    public function renderConfirmationPage(): Response
    {
        return new Response($this->twig->render('confirmation_Email.html.twig'));
    }
}
