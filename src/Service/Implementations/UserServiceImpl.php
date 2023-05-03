<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Dto\UserDto;
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

    public function getUserByUsername(string $username): ?UserDto
    {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return null;
        }

        return $this->transformer->transformUserIntoUserDto($user);
    }

    public function updateUser(User $user): bool
    {
        return $this->userRepository->updateUserRepo($user);
    }
}
