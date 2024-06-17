<?php

namespace App\Repository;

use App\Entity\GameMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMatch::class);
    }

    public function findRankingBySportAndSeason(int $sportId, int $seasonId)
    {
        return $this->createQueryBuilder('gm')
            ->select(
                't.name',
                'SUM(tmg.points) as totalPoints',
                'COUNT(tmg.id) as gamesPlayed',
                'SUM(tmg.score) as totalScore',
                'SUM(CASE WHEN tmg.points = 3 THEN 1 ELSE 0 END) as gamesWon',
                'SUM(CASE WHEN tmg.points = 1 THEN 1 ELSE 0 END) as gamesDrawn',
                'SUM(CASE WHEN tmg.points = 0 THEN 1 ELSE 0 END) as gamesLost'
            )
            ->join('gm.teams', 'tmg')
            ->join('tmg.team', 't')
            ->where('gm.sport = :sportId')
            ->andWhere('gm.season = :seasonId')
            ->andWhere('gm.status = 1')
            ->setParameter('sportId', $sportId)
            ->setParameter('seasonId', $seasonId)
            ->groupBy('t.id')
            ->addOrderBy('totalPoints', 'DESC')
            ->addOrderBy('totalScore', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function countBySportAndSeason($sport, $season)
    {
        return $this->createQueryBuilder('gm')
            ->select('COUNT(gm.id)')
            ->where('gm.sport = :sport')
            ->andWhere('gm.season = :season')
            ->setParameter('sport', $sport)
            ->setParameter('season', $season)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function add(GameMatch $match)
    {
        $this->getEntityManager()->persist($match);
    }
    public function save()
    {
        $this->getEntityManager()->flush();
    }
    public function remove(GameMatch $match, bool $flush = true)
    {
        $this->getEntityManager()->remove($match);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}