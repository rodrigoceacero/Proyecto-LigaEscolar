<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Form\GameMatchType;
use App\Repository\GameMatchRepository;
use App\Repository\TeamMatchGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class GameMatchController extends AbstractController
{
    #[Route('/match', name: 'matchs')]
    public function index(
        Request $request,
        GameMatchRepository $gameMatchRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $query = $gameMatchRepository->findByNamePaginate($search);

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('match/listAjax.html.twig', [
                'pagination' => $pagination
            ]);
            $found = count($pagination) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('match/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/match/edit/{id}', name: 'match_edit')]
    final public function edit(
        Request $request,
        GameMatchRepository $gameMatchRepository,
        TeamMatchGameRepository $teamMatchGameRepository,
        GameMatch $match
        ): Response
    {
        $form = $this->createForm(GameMatchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($match->getTeams() as $teamMatchGame) {
                $teamMatchGameRepository->save($teamMatchGame, false);
            }
            $gameMatchRepository->save($match);
            $this->addFlash('updated', 'Partido actualizado correctamente');
            return $this->redirectToRoute('matchs');
        }

        return $this->render('match/edit.html.twig', [
            'form' => $form->createView(),
            'match' => $match,
        ]);
    }
}