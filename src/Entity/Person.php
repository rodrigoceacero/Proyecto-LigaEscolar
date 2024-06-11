<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
#[ORM\Table(name: 'person')]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    private string $firstName;
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Los apellidos son obligatorios')]
    private string $lastName;
    #[ORM\Column(type: 'boolean')]
    private bool $isPlayer;
    #[ORM\Column(type: 'boolean')]
    private bool $isTeacher;
    #[ORM\ManyToOne( inversedBy: 'players')]
    #[Assert\NotBlank(message: 'El equipo es obligatorio')]
    private ?Team $team = null;
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (!$this->isPlayer && !$this->isTeacher) {
            $context->buildViolation('Debes seleccionar al menos una opción.')
                ->atPath('isPlayer')
                ->addViolation();
        }
    }

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