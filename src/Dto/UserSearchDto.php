<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class UserSearchDto
{
    private ?string $username;

    private ?string $role = 'ROLE_USER';

    private ?string $image = 'DefaultUser.png';

    private ?string $registrationDate;

    private ?string $friendState = 'none';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRegistrationDate(): ?string
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?DateTime $registrationDate): self
    {
        $this->registrationDate = $registrationDate->format('d/m/Y');

        return $this;
    }

    public function getFriendState(): ?string
    {
        return $this->friendState;
    }

    public function setFriendState(?string $friendState): self
    {
        $this->friendState = $friendState;
        return $this;
    }
}
