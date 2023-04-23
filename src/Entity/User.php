<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: "username", message: 'user.username.taken')]
#[UniqueEntity(fields: "email", message: 'user.email.taken')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?string $role = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isVerified = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $bio;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image;

    #[ORM\Column(type: 'date')]
    private \DateTime $registrationDate;

    public function __construct()
    {
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [];
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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }
    public function setIsVerified(?bool $isVerified): User
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): User
    {
        $this->bio = $bio;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(?string $image): User
    {
        $this->image = $image;
        return $this;
    }

    public function getRegistrationDate(): \DateTime
    {
        return $this->registrationDate;
    }
    public function setRegistrationDate(\DateTime $registrationDate): User
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }




    /**
    * @see UserInterface
    */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
