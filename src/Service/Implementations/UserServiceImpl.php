<?php

namespace App\Service\Implementations;

use App\Dto\RequestDtoUsers;
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
        $user->setRole('User');
        $user->setVerified(false);
        if ($this->userRepository->createUser($user)) {
            return true;
        }
        return false;
    }

    public function addAssociateToDB(User $user, array $userHotels): bool
    {
        $this->encodePassword($user);
        $user->setRegistrationDate(date_create_from_format('Y/m/d', date('Y/m/d')));
        foreach ($userHotels as $hotel) {
            $user->addHotel($hotel);
            $hotel->addAssociate($user);
        }
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

    public function updateUser(User $user): bool
    {
        if ($this->userRepository->updateUserRepo($user)) {
            return true;
        }
        return false;
    }

    public function getSortedEmployees(RequestDtoUsers $requestDto): array
    {
        if ($requestDto->getHotelId() !== 0) {
                $query = 'h.id = ' . $requestDto->getHotelId() . " and u.roles != 'ROLE_OWNER'";
        } else {
                $query = "u.roles != 'ROLE_OWNER'";
        }

        $users = $this->userRepository->getSortedEmployees($query, $requestDto);
        $userDto = [];
        foreach ($users as $user) {
            $userDto[] = $this->transformer->transformUserIntoUserDto($user);
        }

        return $userDto;
    }

    public function verifyEmailForResend(string $email): bool
    {
        $verifiedArray = $this->userRepository->getEmailVerification($email);

        if (!empty($verifiedArray)) {
            if ($verifiedArray[0]['isVerified'] === true) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function getUserWithEmail(string $email): ?User
    {
        return $this->userRepository->getUserWithEmail($email);
    }

    public function deleteUsers(array $users): void
    {
        $this->userRepository->deleteUsers($users);
    }

    public function getUserListNumberOfPages(int $hotelId): int
    {
        if ($hotelId !== 0) {
            $query = 'h.id = ' . $hotelId . " and u.roles != 'ROLE_OWNER'";
        } else {
            $query = "u.roles != 'ROLE_OWNER'";
        }

        return intdiv(sizeof($this->userRepository->getAllEmployees($query)), 5) -
            (sizeof($this->userRepository->getAllEmployees($query)) % 5 === 0) + 1;
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->getUserById($userId);
    }
}
