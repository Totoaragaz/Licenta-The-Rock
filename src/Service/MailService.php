<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface MailService
{
    public function confirmEmail(Request $request, int $id, string $email): bool;

    public function sendEmail(User $user): void;

    public function getConfirmationRedirectRoute(Request $request): string;
}
