<?php

namespace App\Transformer;

use App\Dto\UserProfileDto;
use App\Dto\UserSearchDto;
use App\Entity\User;

class UserTransformer
{
    public function transformUserIntoUserProfileDto(User $user, $threads): UserProfileDto
    {
        return (new UserProfileDto())
            ->setUsername($user->getUsername())
            ->setImage($user->getImage())
            ->setBio($user->getBio())
            ->setRole($user->getRole())
            ->setRegistrationDate($user->getRegistrationDate())
            ->setThreads($threads)
            ->setNumberOfPages();
    }

    public function transformUserIntoUserSearchDto(User $user): UserSearchDto
    {
        return (new UserSearchDto())
            ->setUsername($user->getUsername())
            ->setImage($user->getImage())
            ->setRole($user->getRole())
            ->setRegistrationDate($user->getRegistrationDate());
    }
}
