<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_match')]
class GameMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $schedule;
    #[ORM\Column(type: 'string')]
    private ?string $location;
    #[ORM\Column(type: 'string')]
    private ?string $status;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $details;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSchedule(): \DateTime
    {
        return $this->schedule;
    }

    public function setSchedule(\DateTime $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function setDetails(string $details): void
    {
        $this->details = $details;
    }


}