<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name:'teams')]
    public function index(
        Request $request,
        TeamRepository $teamRepository
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $teams = $teamRepository->findByName($search);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('team/listAjax.html.twig', ['teams' => $teams]);
            $found = count($teams) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('team/list.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/team/new', name: 'new_team')]
    public function new(
        Request $request,
        TeamRepository $teamRepository): Response
    {
        $team = new Team();
        $teamRepository->add($team);

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->save();
            $this->addFlash('success', 'Equipo creado con éxito');
        }
        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/mission/{code}', name: 'mission_edit')]
//    final public function edit(
//        Request $request,
//        TeamRepository $teamRepository,
//        Team $team): Response
//    {
//        $form = $this->createForm(MissionType::class, $mission);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $missionRepository->save();
//        }
//        return $this->render('mission/edit.html.twig', [
//            'form' => $form->createView(),
//            'mission' => $mission
//        ]);
//    }
//
//    #[Route('/team/delete/{code}', name: 'delete_team')]
//    public function delete(
//        Request $request,
//        MissionRepository $missionRepository,
//        Mission $mission
//    ) : Response
//    {
//        $form = $this->createForm(MissionType::class, $mission);
//
//        if ($request->request->has('confirmar')) {
//            try{
//                $missionRepository->remove($mission);
//                $missionRepository->save();
//                $this->addFlash('sucess', 'Misión eliminada con éxito');
//                return $this->redirectToRoute('mission_list');
//            }catch (\Exception $e){
//                $this->addFlash('error', 'No se ha podido eliminar la misión');
//            }
//        }
//        return $this->render('mission/delete.html.twig', [
//            'mission' => $mission
}