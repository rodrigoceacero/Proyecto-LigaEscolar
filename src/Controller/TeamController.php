<?php


namespace App\Controller;


use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class TeamController extends AbstractController
{
    #[Route('/team', name:'teams')]
    public function index(
        Request $request,
        TeamRepository $teamRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $query = $teamRepository->findByNamePaginate($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $logos = [];
        foreach ($pagination as $key => $team) {
            if ($team->getLogo() !== null) {
                $logos[$key] = base64_encode(stream_get_contents($team->getLogo()));
            } else {
                $logos[$key] = null;
            }
        }

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('team/listAjax.html.twig',
                ['pagination' => $pagination]
            );
            $found = count($pagination) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('team/list.html.twig', [
            'pagination' => $pagination,
            'logos' => $logos
        ]);
    }

    #[Route('/team/new', name: 'new_team')]
    public function new(
        Request $request,
        TeamRepository $teamRepository): Response
    {
        $edit = 0;
        $team = new Team();
        $teamRepository->add($team);

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['logo']->getData();
            if ($file) {
                $logoData = file_get_contents($file);
                $team->setLogo($logoData);
            }
            $teamRepository->save();
            $this->addFlash('success', 'Equipo creado con éxito');
            return $this->redirectToRoute('teams');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear equipo',
            'titleForm' => 'Datos del nuevo equipo',
            'edit' => $edit,
        ]);
    }

    #[Route('/team/edit/{id}', name: 'team_edit')]
    final public function edit(
        Request $request,
        TeamRepository $teamRepository,
        Team $team): Response
    {
        $edit = 1;
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['logo']->getData();
            if ($file) {
                $logoData = file_get_contents($file);
                $team->setLogo($logoData);
            }
            $teamRepository->save($team);
            $this->addFlash('updated', 'Equipo actualizado correctamente');
            return $this->redirectToRoute('teams');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
            'team' => $team,
            'title' => 'Editar equipo',
            'edit' => $edit,
        ]);
    }

    #[Route('/team/delete/{id}', name: 'delete_team', methods: ['POST'])]
    public function delete(
        Request $request,
        TeamRepository $teamRepository,
        Team $team
    ): JsonResponse
    {
        if ($request->isMethod('POST') && $request->getContent()) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['confirmar'])) {
                try {
                    $teamRepository->remove($team, true);
                    return new JsonResponse(['status' => 'success', 'message' => 'Se ha borrado la temporada correctamente']);
                } catch (\Exception $e) {
                    return new JsonResponse(['status' => 'error', 'message' => 'No se ha podido borrar'], 500);
                }
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Petición invalidad'], 400);
    }
}
