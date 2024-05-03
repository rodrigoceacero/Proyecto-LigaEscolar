<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'string')]
    private ?string $username;
    #[ORM\Column(type: 'string')]
    private ?string $email;
    #[ORM\Column(type: 'string')]
    private ?string $password;
    #[ORM\Column(type: 'boolean')]
    private ?bool $isDeveloper;
    #[ORM\Column(type: 'boolean')]
    private ?bool $isAdmin;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isDeveloper(): bool
    {
        return $this->isDeveloper;
    }

    public function setIsDeveloper(bool $isDeveloper): void
    {
        $this->isDeveloper = $isDeveloper;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }
        if ($this->isDeveloper()) {
            $roles[] = 'ROLE_DEVELOPER';
        }
        return array_unique($roles);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // Don't do anything
    }

    public function getUserIdentifier(): string
    {
        return $this->getUserName();
    }

}