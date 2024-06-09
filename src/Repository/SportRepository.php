<?php

namespace App\Repository;

use App\Entity\Sport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
class SportRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sport::class);
    }

    public function findByNameActive(string $name)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->andWhere('s.active = true')
            ->setParameter('name', $name)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNameInactive(string $name)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->andWhere('s.active = false')
            ->setParameter('name', $name)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findByActive(){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.duration')
            ->where('s.active = true')
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
    }

    public function findByinactive(){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.duration')
            ->where('s.active = false')
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
    }

    public function add(Sport $sport)
    {
        $this->getEntityManager()->persist($sport);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(Sport $sport, bool $flush = true)
    {
        $this->getEntityManager()->remove($sport);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}