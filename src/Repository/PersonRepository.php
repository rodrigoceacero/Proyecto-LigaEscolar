<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findByName(string $name)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName, p.number, p.isPlayer, p.isTeacher, team.id as teamId, team.name as teamName')
            ->join('p.team', 'team')
            ->where('p.firstName LIKE :name OR p.lastName LIKE :name')
            ->setParameter('name', $name)
            ->orderBy('p.firstName', 'ASC')
            ->addOrderBy('p.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByTeamAndName(int $teamId, string $name)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName, p.number, p.isPlayer, p.isTeacher, team.id as teamId, team.name as teamName')
            ->join('p.team', 'team')
            ->where('p.team = :teamId')
            ->andWhere('p.firstName LIKE :name OR p.lastName LIKE :name')
            ->setParameter('teamId', $teamId)
            ->setParameter('name', $name)
            ->orderBy('p.firstName', 'ASC')
            ->addOrderBy('p.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function add(Person $person)
    {
        $this->getEntityManager()->persist($person);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(Person $person, bool $flush = true)
    {
        $this->getEntityManager()->remove($person);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}