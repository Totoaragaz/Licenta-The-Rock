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
            ->setUsername($user->getUsername())
            ->setImage($user->getImage())
            ->setBio($user->getBio())
            ->setRole($user->getRole())
            ->setRegistrationDate($user->getRegistrationDate());
    }
}
