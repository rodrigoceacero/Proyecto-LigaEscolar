<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'team_match_game')]
class TeamMatchGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'integer')]
    private int $orderNumber;
    #[ORM\Column(type: 'integer')]
    private int $points;
    #[ORM\Column(type: 'integer')]
    private int $score;
    #[ORM\ManyToOne(inversedBy: 'team')]
    private ?GameMatch $gameMatch = null;
    #[ORM\ManyToOne(inversedBy: 'matchs')]
    private ?Team $team = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getGameMatch(): ?GameMatch
    {
        return $this->gameMatch;
    }

    public function setGameMatch(?GameMatch $gameMatch): static
    {
        $this->gameMatch = $gameMatch;

        return $this;
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