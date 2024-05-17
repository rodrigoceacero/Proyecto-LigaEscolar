<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sport')]
class Sport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    #[ORM\Column(type: 'string')]
    private ?string $name;
    #[ORM\Column(type: 'integer')]
    private ?int $duration;
    #[ORM\OneToMany(targetEntity: GameMatch::class, mappedBy: 'sport')]
    private Collection $matchs;

    public function __construct()
    {
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

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
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
}