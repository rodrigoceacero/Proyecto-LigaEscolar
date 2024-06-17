<?php

namespace App\Repository;

use App\Entity\Season;
use App\Entity\Sport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function findByDescription(string $description)
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.description, s.startDate, s.endDate')
            ->where('s.description LIKE :description')
            ->setParameter('description', $description)
            ->getQuery()
            ->getResult();
    }

    public function findByDescriptionPaginate(string $description)
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.description, s.startDate, s.endDate')
            ->where('s.description LIKE :description')
            ->setParameter('description', $description)
            ->getQuery();
    }

    public function findBySport(Sport $sport): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.matchs', 'm')
            ->andWhere('m.sport = :sport')
            ->setParameter('sport', $sport)
            ->orderBy('s.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function add(Season $season)
    {
        $this->getEntityManager()->persist($season);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(Season $season, bool $flush = true)
    {
        $this->getEntityManager()->remove($season);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}