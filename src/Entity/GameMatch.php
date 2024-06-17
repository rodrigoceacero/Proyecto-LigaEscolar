<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\Column(type: 'boolean')]
    private ?bool $status;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $details;
    #[ORM\ManyToOne(inversedBy: 'matchs')]
    private ?Season $season = null;
    #[ORM\ManyToOne(inversedBy: 'matchs')]
    private ?Sport $sport = null;
    #[ORM\OneToMany(targetEntity: TeamMatchGame::class, mappedBy: 'gameMatch')]
    private Collection $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
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

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection<int, TeamMatchGame>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(TeamMatchGame $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setGameMatch($this);
        }

        return $this;
    }

    public function removeTeam(TeamMatchGame $team): static
    {
        if ($this->teams->removeElement($team)) {
            if ($team->getGameMatch() === $this) {
                $team->setGameMatch(null);
            }
        }

        return $this;
    }
}