<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Entity\TeamMatchGame;
use App\Form\GenerateMatchType;
use App\Repository\GameMatchRepository;
use App\Repository\TeamMatchGameRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateMatchController extends AbstractController
{
    #[Route('/generatematches', name: 'generate_matches')]
    public function generateMatches(
        Request $request,
        TeamRepository $teamRepository,
        GameMatchRepository $gameMatchRepository,
        TeamMatchGameRepository $teamMatchRepository,
    ): Response
    {
        $form = $this->createForm(GenerateMatchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $sport = $data['sport'];
            $season = $data['season'];

            $existingMatches = $gameMatchRepository->countBySportAndSeason($sport, $season);

            if ($existingMatches > 0) {
                $this->addFlash('error', 'Ya hay partidos creados');
                return $this->redirectToRoute('generate_matches');
            }

            $teams = $teamRepository->findActiveBySportAndSeason($sport, $season);

            $teamCount = count($teams);
            if ($teamCount < 2) {
                $this->addFlash('error', 'No hay equipos suficientes para generar partidos');
                return $this->redirectToRoute('ranking');
            }

            $rounds = $teamCount - 1;

            $seasonYear = (int)substr($season->getDescription(), 0, 4);

            $startDate = new \DateTime("$seasonYear-09-01");
            $endDate = new \DateTime(($seasonYear + 1) . "-07-01");

            for ($i = 0; $i < $rounds; $i++) {
                for ($j = 0; $j < $teamCount / 2; $j++) {
                    $local = ($i + $j) % $rounds;
                    $visiting = ($rounds - $j + $i) % $rounds;

                    if ($j == 0) {
                        $visiting = $rounds;
                    }

                    $randomTimestamp = mt_rand($startDate->getTimestamp(), $endDate->getTimestamp());
                    $randomDate = new \DateTime();
                    $randomDate->setTimestamp($randomTimestamp);

                    $gameMatch = new GameMatch();
                    $gameMatch->setSport($sport);
                    $gameMatch->setSeason($season);
                    $gameMatch->setSchedule($randomDate);
                    $gameMatch->setLocation($teams[$local]->getSchool());
                    $gameMatch->setStatus(false);
                    $gameMatch->setDetails($teams[$local]->getName() . ' vs ' . $teams[$visiting]->getName());

                    $gameMatchRepository->add($gameMatch);

                    $orderNumber1 = rand(0, 1);
                    $orderNumber2 = $orderNumber1 === 1 ? 0 : 1;

                    $teamMatch1 = new TeamMatchGame();
                    $teamMatch1->setGameMatch($gameMatch);
                    $teamMatch1->setTeam($teams[$local]);
                    $teamMatch1->setOrderNumber($orderNumber1);
                    $teamMatch1->setPoints(0);
                    $teamMatch1->setScore(0);

                    $teamMatch2 = new TeamMatchGame();
                    $teamMatch2->setGameMatch($gameMatch);
                    $teamMatch2->setTeam($teams[$visiting]);
                    $teamMatch2->setOrderNumber($orderNumber2);
                    $teamMatch2->setPoints(0);
                    $teamMatch2->setScore(0);

                    $teamMatchRepository->add($teamMatch1);
                    $teamMatchRepository->add($teamMatch2);
                }
            }

            $gameMatchRepository->save();
            $teamMatchRepository->save();

            $this->addFlash('success', 'Cruces generados correctamente');

            return $this->redirectToRoute('generate_matches');
        }

        return $this->render('generate/generatematches.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}