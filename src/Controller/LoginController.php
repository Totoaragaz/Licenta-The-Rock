<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class LoginController
{
    public function __construct(private Environment $twig)
    {
    }

    #[Route(path: '/login', name: 'login')]
    public function renderLogInScreen(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $resendEmail = false;
        $success = $request->query->get('resentEmail', false);

        if ($error instanceof CustomUserMessageAccountStatusException) {
            $resendEmail = true;
        }

        //phpinfo();

        return new Response(
            $this->twig->render(
                'login.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'resendEmail' => $resendEmail,
                    'success' => $success
                ]
            )
        );
    }
}
