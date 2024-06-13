<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\Table(name: 'team')]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    private ?string $name;
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre de la escuela es obligatorio')]
    private ?string $school;
    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: 'team')]
    private Collection $players;
    #[ORM\ManyToOne(inversedBy: 'teams')]
    #[Assert\NotBlank(message: 'El deporte es obligatorio')]
    private ?Sport $sport = null;

    #[ORM\ManyToMany(targetEntity: Season::class, mappedBy: 'teams')]
    private Collection $seasons;

    #[ORM\OneToMany(targetEntity: TeamMatchGame::class, mappedBy: 'team')]
    private Collection $matchs;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->matchs = new ArrayCollection();
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

    public function getSchool(): string
    {
        return $this->school;
    }

    public function setSchool(string $school): void
    {
        $this->school = $school;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Person $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Person $player): static
    {
        if ($this->players->removeElement($player)) {
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

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
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): static
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->addTeam($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): static
    {
        if ($this->seasons->removeElement($season)) {
            $season->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamMatchGame>
     */
    public function getMatchs(): Collection
    {
        return $this->matchs;
    }

    public function addMatch(TeamMatchGame $match): static
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
            $match->setTeam($this);
        }

        return $this;
    }

    public function removeMatch(TeamMatchGame $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getTeam() === $this) {
                $match->setTeam(null);
            }
        }

        return $this;
    }
}