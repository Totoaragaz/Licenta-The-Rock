<?php

namespace App\Transformer;

use App\Dto\UserDto;
use App\Entity\User;

class UserTransformer
{
    public function transformUserIntoUserDto(User $user): UserDto
    {
        return (new UserDto())
            ->setId($user->getId())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setEmail($user->getEmail())
            ->setAddress($user->getAddress())
            ->setImage($user->getImage())
            ->setBio($user->getBio())
            ->setBirthday($user->getBirthday())
            ->setRoles(ucfirst(strtolower(str_replace('ROLE_','', $user->getRoles()[0]))))
            ->setRegistrationDate($user->getRegistrationDate());
    }
}
