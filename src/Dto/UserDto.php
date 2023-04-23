<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class UserDto
{
    private ?string $username;

    private ?string $password;

    private ?string $email;
    private ?string $bio = '';

    private ?string $image = '';

    private ?string $registrationDate;

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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): UserDto
    {
        $this->roles = $roles;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(DateTime $birthday): UserDto
    {
        $this->birthday = $birthday->format('Y/m/d');

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

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): UserDto
    {
        $this->gender = $gender;

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

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): UserDto
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getRegistrationDate(): ?string
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?DateTime $registrationDate): UserDto
    {
        $this->registrationDate = $registrationDate->format('Y/m/d');

        return $this;
    }
}
