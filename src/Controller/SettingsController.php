<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SettingsController
{
    public function __construct(
        private Environment $twig,
    )
    {
    }

    #[Route(path: '/settings', name: 'settings')]
    public function renderHomeScreen(Request $request): Response
    {
        $darkMode = $request->query->get('darkMode');
        return new Response($this->twig->render('settings.html.twig',
            ['darkMode' => $darkMode]
        ));
    }
}