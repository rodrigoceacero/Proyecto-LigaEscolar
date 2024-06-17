<?php 

namespace App\Controller;

use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameMatchRepository;
use App\Repository\SeasonRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;

class PdfController extends AbstractController
{
    #[Route('/ranking/sport/{idsport}/season/{idseason}/pdf', name: 'generate_pdf')]
    public function generatePdf(
        int $idsport,
        int $idseason,
        GameMatchRepository $gameMatchRepository,
        SportRepository $sportRepository,
        SeasonRepository $seasonRepository,
        TeamRepository $teamRepository,
        Pdf $knpSnappyPdf
    ): Response {
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
            if ($team1['totalPoints'] == $team2['totalPoints']) {
                if ($team1['gamesPlayed'] == $team2['gamesPlayed']) {
                    if ($team1['totalScore'] == $team2['totalScore']) {
                        return strcmp($team1['name'], $team2['name']);
                    }
                    return $team2['totalScore'] - $team1['totalScore'];
                }
                return $team2['gamesPlayed'] - $team1['gamesPlayed'];
            }
            return $team2['totalPoints'] - $team1['totalPoints'];
        });

        $html = $this->renderView('generate/generatepdf.html.twig', [
            'ranking' => $ranking,
            'sport' => $sport,
            'season' => $season,
        ]);

        $fileName = sprintf('Clasificacion_%s_%s.pdf', $sport->getName(), $season->getDescription());

        return new Response(
            $knpSnappyPdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
            ]
        );
    }
}