<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class UserDto
{
    private ?string $username;

    private ?string $password = null;

    private ?string $email;

    private ?string $role = 'ROLE_USER';

    private ?string $bio = '';

    private ?string $image = 'DefaultUser.png';

    private ?string $registrationDate;

    private ?bool $verified = true;

    private ?bool $darkMode = null;

    private ?bool $mainColumn = null;

    private ?bool $chatColumn = null;

    private ?bool $friendColumn = null;


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): UserDto
    {
        $this->address = $address;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): UserDto
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): UserDto
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): UserDto
    {
        $this->password = $password;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): UserDto
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): UserDto
    {
        $this->image = $image;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(?bool $verified): UserDto
    {
        $this->verified = $verified;

        return $this;
    }

    public function getRegistrationDate(): ?string
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?DateTime $registrationDate): UserDto
    {
        $this->registrationDate = $registrationDate->format('d/m/Y');

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDarkMode(): ?bool
    {
        return $this->darkMode;
    }

    /**
     * @param bool|null $darkMode
     * @return UserDto
     */
    public function setDarkMode(?bool $darkMode): UserDto
    {
        $this->darkMode = $darkMode;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMainColumn(): ?bool
    {
        return $this->mainColumn;
    }

    /**
     * @param bool|null $mainColumn
     * @return UserDto
     */
    public function setMainColumn(?bool $mainColumn): UserDto
    {
        $this->mainColumn = $mainColumn;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getChatColumn(): ?bool
    {
        return $this->chatColumn;
    }

    /**
     * @param bool|null $chatColumn
     * @return UserDto
     */
    public function setChatColumn(?bool $chatColumn): UserDto
    {
        $this->chatColumn = $chatColumn;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFriendColumn(): ?bool
    {
        return $this->friendColumn;
    }

    /**
     * @param bool|null $friendColumn
     * @return UserDto
     */
    public function setFriendColumn(?bool $friendColumn): UserDto
    {
        $this->friendColumn = $friendColumn;
        return $this;
    }


}
