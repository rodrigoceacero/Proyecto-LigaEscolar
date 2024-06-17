<?php

namespace App\Repository;

use App\Entity\GameMatch;
use App\Entity\TeamMatchGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamMatchGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamMatchGame::class);
    }

    public function add(TeamMatchGame $teamMatch)
    {
        $this->getEntityManager()->persist($teamMatch);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(TeamMatchGame $teamMatch, bool $flush = true)
    {
        $this->getEntityManager()->remove($teamMatch);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}