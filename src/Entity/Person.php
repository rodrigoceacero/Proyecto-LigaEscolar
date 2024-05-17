<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'person')]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string')]
    private string $firstName;
    #[ORM\Column(type: 'string')]
    private string $lastName;
    #[ORM\Column(type: 'integer')]
    private int $number;
    #[ORM\Column(type: 'boolean')]
    private bool $isPlayer;
    #[ORM\Column(type: 'boolean')]
    private bool $isTeacher;
    #[ORM\ManyToOne( inversedBy: 'players')]
    private ?Team $team = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function isPlayer(): bool
    {
        return $this->isPlayer;
    }

    public function setIsPlayer(bool $isPlayer): void
    {
        $this->isPlayer = $isPlayer;
    }

    public function isTeacher(): bool
    {
        return $this->isTeacher;
    }

    public function setIsTeacher(bool $isTeacher): void
    {
        $this->isTeacher = $isTeacher;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }
}