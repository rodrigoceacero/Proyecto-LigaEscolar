<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findByNamePaginate(string $name){
        return $this->createQueryBuilder('t')
            ->select('t, sport, season')
            ->leftJoin('t.sport', 'sport')
            ->leftJoin('t.seasons', 'season')
            ->where('t.name LIKE :name')
            ->setParameter('name', $name)
            ->orderBy('t.name')
            ->getQuery();
    }

    public function findByName(string $name){
        return $this->createQueryBuilder('t')
            ->select('t, sport, season')
            ->leftJoin('t.sport', 'sport')
            ->leftJoin('t.seasons', 'season')
            ->where('t.name LIKE :name')
            ->setParameter('name', $name)
            ->orderBy('t.name')
            ->getQuery()
            ->getResult();
    }

    public function add(Team $team)
    {
        $this->getEntityManager()->persist($team);
    }

    public function save()
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Team $team, bool $flush = true)
    {
        $this->getEntityManager()->remove($team);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
