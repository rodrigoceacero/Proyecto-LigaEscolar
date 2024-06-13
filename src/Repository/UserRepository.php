<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUsernamePaginate(string $username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :username')
            ->setParameter('username', $username)
            ->orderBy('u.username', 'ASC')
            ->getQuery();
    }


    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(User $user, bool $flush = true)
    {
        $this->getEntityManager()->remove($user);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}