<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'season')]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string')]
    private string $description;
    #[ORM\Column(type: 'datetime')]
    private \DateTime $startDate;
    #[ORM\Column(type: 'datetime')]
    private \DateTime $endDate;

    #[ORM\OneToMany(targetEntity: GameMatch::class, mappedBy: 'season')]
    private Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
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
}

