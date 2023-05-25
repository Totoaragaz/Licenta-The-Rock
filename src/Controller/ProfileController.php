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
        $currentPage = $request->query->get('page', 1);

        if ($user->getUsername() == $username) {
            $registrationDate = ' ' . $user->getRegistrationDate()->format('d/m/Y');
            $viewedUser = $this->userManager->transformUserIntoProfileDTO($user);
            return new Response($this->twig->render('profile.html.twig', [
                'user' => $user,
                'viewedUser' => $viewedUser,
                'registrationDate' => $registrationDate,
                'ownProfile' => true,
                'currentPage' => $currentPage,
                'friendState' => ''
            ]));
        } else {
            $viewedUser = $this->userManager->getUserByUsername($username);
            if (!$viewedUser) {
                throw $this->createNotFoundException('User ' . $username . ' does not exist');
            }

            $registrationDate = ' ' . $viewedUser->getRegistrationDate();
            return new Response($this->twig->render('profile.html.twig', [
                'user' => $user,
                'viewedUser' => $viewedUser,
                'registrationDate' => $registrationDate,
                'ownProfile' => false,
                'currentPage' => $currentPage,
                'friendState' => $this->userManager->getFriendState($user, $username)
            ]));
        }
    }

    #[Route(path: '/editProfile', name: 'editProfile')]
    public function renderEditProfileScreen(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $currentPage = $request->query->get('page', 1);

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

        $registrationDate = ' ' . $user->getRegistrationDate()->format('d/m/Y');
        $userDTO = $this->userManager->transformUserIntoProfileDTO($user);

        return new Response($this->twig->render('editProfile.html.twig', [
            'user' => $user,
            'viewedUser' => $userDTO,
            'registrationDate' => $registrationDate,
            'editProfileForm' => $form->createView(),
            'currentPage' => $currentPage
        ]));
    }
}