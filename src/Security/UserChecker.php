<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User as AppUser;
use App\Enum\ErrorMessages;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException(ErrorMessages::ACCOUNT_NOT_VERIFIED->value);
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
