<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    #[Route('/season', name:'seasons')]
    public function index(
        Request $request,
        SeasonRepository $seasonRepository
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $seasons = $seasonRepository->findByDescription($search);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('season/listAjax.html.twig', [
                'seasons' => $seasons
            ]);
            $found = count($seasons) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('season/list.html.twig', [
            'seasons' => $seasons,
        ]);
    }

    #[Route('/season/new', name: 'new_season')]
    public function new(
        Request $request,
        SeasonRepository $seasonRepository): Response
    {
        $season = new Season();
        $seasonRepository->add($season);

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonDescription = $season->getDescription();
            $existSeasonDescription = $seasonRepository->findByDescription($seasonDescription);

            if ($existSeasonDescription){
                $this->addFlash('error', 'Ya existe esa temporada');
                return $this->render('season/new.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Crear temporada',
                    'titleForm' => 'Añadir datos'
                ]);
            }

            $seasonRepository->save();
            $this->addFlash('success', 'Temporada creada correctamente');
            return $this->redirectToRoute('seasons');
        }

        return $this->render('season/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear temporada',
            'titleForm' => 'Añadir datos'
        ]);
    }

    #[Route('/season/edit/{id}', name: 'season_edit')]
    final public function edit(
        Request $request,
        SeasonRepository $seasonRepository,
        Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonDescription = $season->getDescription();
            $existSeasonDescription = $seasonRepository->findOneBy(['description' => $seasonDescription]);

            if ($existSeasonDescription && $existSeasonDescription->getId() !== $season->getId()) {
                $this->addFlash('error', 'Ya existe otra temporada');
                return $this->render('season/new.html.twig', [
                    'form' => $form->createView(),
                    'season' => $season,
                    'title' => 'Editar temporada',
                    'titleForm' => 'Actualizar datos'
                ]);
            }

            $seasonRepository->save($season);
            $this->addFlash('updated', 'Temporada actualizada correctamente');
            return $this->redirectToRoute('seasons');
        }

        return $this->render('season/new.html.twig', [
            'form' => $form->createView(),
            'season' => $season,
            'title' => 'Editar temporada',
            'titleForm' => 'Actualizar datos'
        ]);
    }

    #[Route('/season/delete/{id}', name: 'delete_season', methods: ['POST'])]
    public function delete(
        Request $request,
        SeasonRepository $seasonRepository,
        Season $season
    ): JsonResponse
    {
        if ($request->isMethod('POST') && $request->getContent()) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['confirmar'])) {
                try {
                    $seasonRepository->remove($season, true);
                    return new JsonResponse(['status' => 'success', 'message' => 'Se ha borrado la temporada correctamente']);
                } catch (\Exception $e) {
                    return new JsonResponse(['status' => 'error', 'message' => 'No se ha podido borrar'], 500);
                }
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Petición invalidad'], 400);
    }
}