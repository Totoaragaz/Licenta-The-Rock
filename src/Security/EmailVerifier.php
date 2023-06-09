<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private UserRepository $userRepository;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId(), 'email' => $user->getEmail()],
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    public function handleEmailConfirmation(Request $request, string $id, string $email): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $id, $email);

        $this->userRepository->activateUser($email);
    }
}
