<?php

namespace App\Manager;

use App\Dto\RequestDtoUsers;
use App\Entity\User;
use App\Service\Implementations\MailServiceImpl;
use App\Service\Implementations\UserServiceImpl;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        protected UserServiceImpl             $userService,
        protected MailServiceImpl             $mailService,
        protected UserPasswordHasherInterface $passwordHasher,
        protected ManagerRegistry             $doctrine
    )
    {
    }

    public function saveUser(User $user): bool
    {
        if (true === $this->userService->addUserToDB($user)) {
            $this->mailService->sendEmail($user);

            return true;
        }

        return false;
    }

    public function getUserMode(int $userId): bool
    {
        return $this->userService->getUserMode($userId);
    }
}
