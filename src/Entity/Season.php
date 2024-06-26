<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'season')]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[Assert\NotBlank(message: 'La temporada es obligatoria')]
    #[Assert\Regex(
        pattern: '/^\d{4}\/\d{2}$/',
        message: 'La temporada debe tener el tener el formato "2023/24"'
    )]
    #[ORM\Column(type: 'string')]
    private string $description;
    #[Assert\NotBlank(message: 'La fecha de inicio es obligatoria')]
    #[Assert\Type("\DateTimeInterface", message: 'La fecha de inicio debe ser una fecha válida')]
    #[ORM\Column(type: 'datetime')]
    private \DateTime $startDate;
    #[Assert\NotBlank(message: 'La fecha de fin es obligatoria')]
    #[Assert\Type("\DateTimeInterface", message: 'La fecha de fin debe ser una fecha válida')]
    #[ORM\Column(type: 'datetime')]
    private \DateTime $endDate;

    #[ORM\OneToMany(targetEntity: GameMatch::class, mappedBy: 'season')]
    private Collection $matchs;

    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'seasons')]
    private Collection $teams;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
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
            $match->setSeason($this);
        }
        return $this;
    }

    public function removeMatch(GameMatch $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getSeason() === $this) {
                $match->setSeason(null);
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
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->teams->removeElement($team);

        return $this;
    }
}

