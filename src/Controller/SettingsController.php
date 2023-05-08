<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SettingsFormType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SettingsController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private UserManager $userManager
    )
    {
    }

    #[Route(path: '/settings', name: 'settings')]
    public function renderSettingsScreen(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(SettingsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            $session->set('darkMode', $user->getDarkMode());
            $this->userManager->updateUserSave($user);
        }

        return new Response($this->twig->render('settings.html.twig', [
            'user' => $user,
            'settingsForm' => $form->createView()
        ]));
    }
}