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

    public function saveAssociate(User $user, array $userHotels): bool
    {
        if (true === $this->userService->addAssociateToDB($user, $userHotels)) {
            $this->mailService->sendEmail($user);

            return true;
        }

        return false;
    }

    public function updateUserSave(User $user): bool
    {
        if ($this->userService->updateUser($user)) {

            return true;
        }

        return false;
    }

    public function verifyEmailForResend(string $email): bool
    {
        return $this->userService->verifyEmailForResend($email);
    }

    public function resendEmail(string $email): bool
    {
        $user = $this->userService->getUserWithEmail($email);
        if ($user !== null) {
            $this->mailService->sendEmail($user);

            return true;
        }

        return false;
    }
    public function deleteUsers(array $users): void
    {
        $this->userService->deleteUsers($users);
    }

    public function getUserListNumberOfPages(int $hotelId): int
    {
        return $this->userService->getUserListNumberOfPages($hotelId);
        // return intdiv(sizeof($users), 5) - (sizeof($users) % 5 === 0) + 1;
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userService->getUserById($userId);
    }
}
