<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Dto\UserProfileDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Transformer\UserTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceImpl
{
    protected UserRepository $userRepository;
    protected EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;
    protected UserTransformer $transformer;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserTransformer $transformer
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->transformer = $transformer;
    }

    public function addUserToDB(User $user): bool
    {
        $this->encodePassword($user);
        $user->setRegistrationDate(date_create_from_format('Y/m/d', date('Y/m/d')));
        $user->setRole('ROLE_USER');
        $user->setVerified(false);
        if ($this->userRepository->createUser($user)) {
            return true;
        }
        return false;
    }

    public function encodePassword(User $user): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPassword())
        );
    }

    public function getUserMode(int $userId): bool
    {
        return $this->userRepository->getUserMode($userId);
    }

    public function getMainColumn(int $userId): bool
    {
        return $this->userRepository->getMainColumn($userId);
    }

    public function getFriendColumn(int $userId): bool
    {
        return $this->userRepository->getFriendColumn($userId);
    }

    public function getChatColumn(int $userId): bool
    {
        return $this->userRepository->getChatColumn($userId);
    }

    public function getUserByUsername(string $username): ?UserProfileDto
    {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return null;
        }

        return $this->transformer->transformUserIntoUserProfileDto($user);
    }

    public function updateUser(User $user): bool
    {
        return $this->userRepository->updateUserRepo($user);
    }

    public function removeFriend(User $user, string $friendUsername): bool
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        $user->removeFriend($friend);
        $friend->removeFriend($user);

        return $this->userRepository->updateUserRepo($user) && $this->userRepository->updateUserRepo($friend);
    }

    public function acceptFriendRequest(User $user, string $friendUsername): bool
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        $user->addOutgoingFriendRequest($friend);
        $friend->addIncomingFriendRequest($user);

        return $this->userRepository->updateUserRepo($user) && $this->userRepository->updateUserRepo($friend);
    }

    public function declineFriendRequest(User $user, string $friendUsername): bool
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        $user->removeIncomingFriendRequest($friend);
        $friend->removeOutgoingFriendRequest($user);

        return $this->userRepository->updateUserRepo($user) && $this->userRepository->updateUserRepo($friend);
    }

    public function revokeFriendRequest(User $user, string $friendUsername): bool
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        $user->removeOutgoingFriendRequest($friend);
        $friend->removeIncomingFriendRequest($user);

        return $this->userRepository->updateUserRepo($user) && $this->userRepository->updateUserRepo($friend);
    }

    public function sendFriendRequest(User $user, string $friendUsername): bool
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        $user->addOutgoingFriendRequest($friend);
        $friend->addIncomingFriendRequest($user);

        return $this->userRepository->updateUserRepo($user) && $this->userRepository->updateUserRepo($friend);
    }

    public function getFriendState(User $user, string $friendUsername): string
    {
        $friend = $this->userRepository->getUserByUsername($friendUsername);
        if ($user->getFriends()->contains($friend)) {
            return 'friends';
        } else if ($user->getIncomingFriendRequests()->contains($friend)) {
            return 'incomingRequest';
        } else if ($user->getOutgoingFriendRequests()->contains($friend)) {
            return 'outgoingRequest';
        } else {
            return 'none';
        }
    }

    public function getAllOtherUsers(User $user, int $page): array
    {
        $users = $this->userRepository->getAllOtherUsersWithPage($user->getUsername(), $page);
        if ($users) {
            $userDTOs = [];
            foreach ($users as $resultUser) {
                $userDTO = $this->transformer->transformUserIntoUserSearchDto($resultUser);
                $userDTO->setFriendState($this->getFriendState($user, $resultUser->getUsername()));
                $userDTOs[] = $userDTO;
            }

            return $userDTOs;
        }

        return [];
    }

    public function getAllOtherUsersNumberOfPages(string $username): int
    {
        $users = $this->userRepository->getAllOtherUsers($username);
        return intdiv(sizeof($users), 10) - (sizeof($users) % 5 === 10) + 1;
    }

    public function getSearchedUsers(User $user, string $query, int $page): array
    {
        $users = $this->userRepository->getSearchedUsersWithPage(strtolower($user->getUsername()), $query, $page);
        if ($users) {
            $userDTOs = [];
            foreach ($users as $resultUser) {
                $userDTO = $this->transformer->transformUserIntoUserSearchDto($resultUser);
                $userDTO->setFriendState($this->getFriendState($user, $resultUser->getUsername()));
                $userDTOs[] = $userDTO;
            }

            return $userDTOs;
        }

        return [];
    }

    public function getSearchedUsersNumberOfPages(string $username, string $query): int
    {
        $users = $this->userRepository->getSearchedUsers(strtolower($username), $query);
        return intdiv(sizeof($users), 10) - (sizeof($users) % 10 === 0) + 1;
    }
}
