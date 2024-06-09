<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'sport')]
class Sport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    private ?string $name;
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'La duración del partido es obligatorio')]
    private ?int $duration;
    #[ORM\OneToMany(targetEntity: GameMatch::class, mappedBy: 'sport')]
    private Collection $matchs;
    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'sport')]
    private Collection $teams;
    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

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

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return Collection<int, GameMatch>
     */
    public function getMatchs(): Collection
    {
        return $this->matchs;
    }

    public function addMatch(GameMatch $match): static
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
            $match->setSport($this);
        }

        return $this;
    }

    public function removeMatch(GameMatch $match): static
    {
        if ($this->matchs->removeElement($match)) {
            if ($match->getSport() === $this) {
                $match->setSport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setSport($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            if ($team->getSport() === $this) {
                $team->setSport(null);
            }
        }
 
        return $this;
    }
}