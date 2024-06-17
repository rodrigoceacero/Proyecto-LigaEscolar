<?php

namespace App\Controller;

use App\Repository\GameMatchRepository;
use App\Repository\SeasonRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    #[Route('/ranking', name: 'ranking')]
    public function index(
        SportRepository $sportRepository
    ): Response
    {
        $sports = $sportRepository->findOrderByNameSeasons();

        return $this->render('ranking/listsports.html.twig', [
            'sports' => $sports,
        ]);
    }

    #[Route('/ranking/sport/{id}', name: 'ranking_sport')]
    public function rankingSport(
        int $id,
        SportRepository $sportRepository,
        SeasonRepository $seasonRepository
    ): Response
    {
        $sport = $sportRepository->find($id);

        $seasons = $seasonRepository->findBySport($sport);

        return $this->render('ranking/listseasons.html.twig', [
            'sport' => $sport,
            'seasons' => $seasons,
        ]);
    }

    #[Route('/ranking/sport/{idsport}/season/{idseason}', name: 'ranking_list')]
    public function rankingSeason(
        int $idsport,
        int $idseason,
        GameMatchRepository $gameMatchRepository,
        SportRepository $sportRepository,
        SeasonRepository $seasonRepository,
        TeamRepository $teamRepository
    ): Response
    {
        $sport = $sportRepository->find($idsport);
        $season = $seasonRepository->find($idseason);
        $teams = $teamRepository->findActiveBySportAndSeason($sport, $season);

        $rankingData = $gameMatchRepository->findRankingBySportAndSeason($idsport, $idseason);

        $rankingMap = [];
        foreach ($rankingData as $data) {
            $rankingMap[$data['name']] = $data;
        }

        $ranking = array_map(function($team) use ($rankingMap) {
            return $rankingMap[$team->getName()] ?? [
                'name' => $team->getName(),
                'totalPoints' => 0,
                'gamesPlayed' => 0,
                'gamesWon' => 0,
                'gamesDrawn' => 0,
                'gamesLost' => 0,
                'totalScore' => 0,
            ];
        }, $teams);

        usort($ranking, function ($team1, $team2) {
            if ($team1['gamesPlayed'] == $team2['gamesPlayed']) {
                if ($team1['totalPoints'] == $team2['totalPoints']) {
                    if ($team1['totalScore'] == $team2['totalScore']) {
                        return strcmp($team1['name'], $team2['name']);
                    }
                    return $team2['totalScore'] - $team1['totalScore'];
                }
                return $team2['totalPoints'] - $team1['totalPoints'];
            }
            return $team2['gamesPlayed'] - $team1['gamesPlayed'];
        });

        return $this->render('ranking/list.html.twig', [
            'sport' => $sport,
            'season' => $season,
            'ranking' => $ranking,
        ]);
    }
}