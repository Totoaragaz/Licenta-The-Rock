<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Manager\UserManager;
use App\Service\Implementations\UploadPictureServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ProfileController extends AbstractController
{
    public function __construct(
        private Environment              $twig,
        private UserManager              $userManager,
        private UploadPictureServiceImpl $uploadPictureServiceImpl
    )
    {
    }

    #[Route(path: '/profile/{username}', name: 'profile')]
    public function renderProfileScreen(Request $request, string $username): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getUsername() == $username) {
            $registrationDate = ' ' . $user->getRegistrationDate()->format('d/m/Y');
            return new Response($this->twig->render('profile.html.twig', [
                'mainColumn' => 1,
                'chatColumn' => 1,
                'friendColumn' => 1,
                'viewedUser' => $user,
                'user' => $user,
                'registrationDate' => $registrationDate,
                'ownProfile' => true,
            ]));
        } else {
            $viewedUser = $this->userManager->getUserByUsername($username);
            if (!$viewedUser) {
                throw $this->createNotFoundException('User ' . $username . ' does not exist');
            }

            $registrationDate = ' ' . $viewedUser->getRegistrationDate();
            return new Response($this->twig->render('profile.html.twig', [
                'mainColumn' => 1,
                'chatColumn' => 1,
                'friendColumn' => 1,
                'viewedUser' => $viewedUser,
                'user' => $user,
                'registrationDate' => $registrationDate,
                'ownProfile' => false,
            ]));
        }
    }

    #[Route(path: '/editProfile', name: 'editProfile')]
    public function renderEditProfileScreen(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $registrationDate = ' ' . $user->getRegistrationDate()->format('d/m/Y');

        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('image')->getData();
            $checkbox = $request->get('delete-image-checkbox');

            $oldPicture = $user->getImage();
            if (!$picture && $checkbox && $user->getImage()) {
                $user->setImage('DefaultUser.png');
                $this->uploadPictureServiceImpl->deletePicture($oldPicture);
            } elseif ($picture) {
                $this->uploadPictureServiceImpl->deletePicture($oldPicture);
                $path = $this->uploadPictureServiceImpl->uploadPicture($picture);
                $user->setImage($path);
            }

            if ($this->userManager->updateUserSave($user)) {
                return $this->redirectToRoute('profile', ['username' => $user->getUsername()]);
            }
        }
        return new Response($this->twig->render('editProfile.html.twig', [
                'mainColumn' => 1,
                'chatColumn' => 1,
                'friendColumn' => 1,
                'user' => $user,
                'registrationDate' => $registrationDate,
                'editProfileForm' => $form->createView()
            ]));
    }
}