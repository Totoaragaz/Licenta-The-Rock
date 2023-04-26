<?php

namespace App\Service\Implementations;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Service\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class MailServiceImpl implements MailService
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function sendEmail(User $user): void
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('theRockMailBot@noreply.com', 'The Rock'))
                ->to($user->getEmail())
                ->subject('Verify your Email')
                ->htmlTemplate('confirmationEmail.html.twig')
                ->context([
                    'username' => $user->getUsername(),
                ])
        );
    }

    public function getConfirmationRedirectRoute(Request $request): string
    {
        $id = $request->query->get('id');
        $email = $request->query->get('email');

        if ($this->confirmEmail($request, $id, $email)) {
            return 'login';
        }

        return 'register';
    }

    public function confirmEmail(Request $request, int $id, string $email): bool
    {
        $this->emailVerifier->handleEmailConfirmation($request, $id, $email);

        return true;
    }
}
