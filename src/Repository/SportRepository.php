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

    public function findOrderByName(){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.duration, s.active')
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.duration, s.active')
            ->where('s.name LIKE :name')
            ->orderBy('s.name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

    public function findByNamePagination(string $name){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.duration, s.active')
            ->where('s.name LIKE :name')
            ->orderBy('s.name')
            ->setParameter('name', $name)
            ->getQuery();
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