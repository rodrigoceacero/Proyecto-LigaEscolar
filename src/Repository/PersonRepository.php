<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findByNamePaginate(string $name)
    {
        return $this->createQueryBuilder('p')
        ->select('p.id, p.firstName, p.lastName, p.isPlayer, p.isTeacher, team.id as teamId, team.name as teamName')
        ->join('p.team', 'team')
        ->where('p.firstName LIKE :name OR p.lastName LIKE :name')
        ->setParameter('name', $name)
        ->orderBy('p.firstName', 'ASC')
        ->addOrderBy('p.lastName', 'ASC')
        ->getQuery();
    }

    public function findByName(string $name)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName, p.isPlayer, p.isTeacher, team.id as teamId, team.name as teamName')
            ->join('p.team', 'team')
            ->where('p.firstName LIKE :name OR p.lastName LIKE :name')
            ->setParameter('name', $name)
            ->orderBy('p.firstName', 'ASC')
            ->addOrderBy('p.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function findPersonByTeam(int $teamId)
    {
        return $this->createQueryBuilder('p')
            ->select('p.isPlayer, p.lastName, p.firstName, p.isPlayer, p.isTeacher, t.name')
            ->join('p.team', 't')
            ->join('t.sport', 's')
            ->where('t.id = :teamId')
            ->setParameter('teamId', $teamId)
            ->orderBy('p.firstName', 'ASC')
            ->addOrderBy('p.lastName', 'ASC')
            ->getQuery();
    }

    public function hayProfesorEnEquipo(Team $team): bool
    {
        $count = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->join('p.team', 't')
            ->where('t = :team')
            ->andWhere('p.isTeacher = true')
            ->setParameter('team', $team)
            ->getQuery()
            ->getSingleScalarResult();

        return (int)$count > 0;
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