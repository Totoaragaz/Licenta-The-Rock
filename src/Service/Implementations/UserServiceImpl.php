<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Dto\UserProfileDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Transformer\ThreadTransformer;
use App\Transformer\UserTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceImpl
{

    public function __construct(
        protected UserRepository              $userRepository,
        protected EntityManagerInterface      $entityManager,
        protected UserPasswordHasherInterface $passwordHasher,
        protected UserTransformer             $transformer,
        protected ThreadTransformer           $threadTransformer,
    )
    {
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

    public function getUserByUsername(string $username): ?UserProfileDto
    {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return null;
        }

        return $this->transformUserIntoProfileDto($user);
    }

    public function transformUserIntoProfileDto(User $user): UserProfileDto
    {
        $threads = $user->getThreads();
        $threadDTOs = [];
        foreach ($threads as $thread) {
            $threadDTOs[] = $this->threadTransformer->transformThreadIntoSearchDto($thread);
        }

        return $this->transformer->transformUserIntoUserProfileDto($user, $threadDTOs);
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

    public function getUserObjectByUsername(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }
}
