<?php

namespace App\Tests\Transformer;

use App\Dto\UserProfileDto;
use App\Entity\User;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    public function testTransformUserIntoProfileDto()
    {
        $user = new User();
        $user->setUsername('user');
        $user->setImage('DefaultUser.png');
        $user->setBio('AAA');
        $user->setRole('ROLE_USER');
        $user->setRegistrationDate(date_create('09/11/2001'));

        $transformer = new UserTransformer();
        $generatedUserDto = $transformer->transformUserIntoUserProfileDto($user, []);

        $userDto = new UserProfileDto();
        $userDto->setUsername('user')
        ->setImage('DefaultUser.png')
        ->setBio('AAA')
        ->setRole('ROLE_USER')
        ->setRegistrationDate(date_create('09/11/2001'))
        ->setThreads([])
        ->setNumberOfPages();

        self::assertEquals($userDto, $generatedUserDto);
    }
}