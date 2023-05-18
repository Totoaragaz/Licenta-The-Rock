<?php

namespace App\Manager;

use App\Dto\RequestDtoUsers;
use App\Dto\UserProfileDto;
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

    public function getMainColumn(int $userId): bool
    {
        return $this->userService->getMainColumn($userId);
    }

    public function getFriendColumn(int $userId): bool
    {
        return $this->userService->getFriendColumn($userId);
    }

    public function getChatColumn(int $userId): bool
    {
        return $this->userService->getChatColumn($userId);
    }

    public function getUserByUsername(string $username): ?UserProfileDto
    {
        return $this->userService->getUserByUsername($username);
    }

    public function updateUserSave(User $user): bool
    {
        return $this->userService->updateUser($user);
    }

    public function removeFriend(User $user, string $friend): bool
    {
        return $this->userService->removeFriend($user, $friend);
    }

    public function acceptFriendRequest(User $user, string $friend): bool
    {
        return $this->userService->acceptFriendRequest($user, $friend);
    }

    public function declineFriendRequest(User $user, string $friend): bool
    {
        return $this->userService->declineFriendRequest($user, $friend);
    }

    public function revokeFriendRequest(User $user, string $friend): bool
    {
        return $this->userService->revokeFriendRequest($user, $friend);
    }

    public function sendFriendRequest(User $user, string $friend): bool
    {
        return $this->userService->sendFriendRequest($user, $friend);
    }

    public function getFriendState(User $user, string $friend): string
    {
        return $this->userService->getFriendState($user, $friend);
    }

    public function getAllOtherUsers(User $user, int $page): array
    {
        return $this->userService->getAllOtherUsers($user, $page);
    }

    public function getSearchedUsers(User $user, string $query, int $page): array
    {
        return $this->userService->getSearchedUsers($user, $query, $page);
    }
}
