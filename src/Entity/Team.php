<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'team')]
class Team
{
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'string')]
    private ?string $name;
    #[ORM\Column(type: 'string')]
    private ?string $school;
    #[ORM\Column(type: 'string')]
    private ?User $manager;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSchool(): string
    {
        return $this->school;
    }

    public function setSchool(string $school): void
    {
        $this->school = $school;
    }

    public function getManager(): User
    {
        return $this->manager;
    }

    public function setManager(User $manager): void
    {
        $this->manager = $manager;
    }
}