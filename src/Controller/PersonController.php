<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class PersonController extends AbstractController
{
    #[Route('/person', name: 'people')]
    public function index(
        Request $request,
        PersonRepository $personRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $query = $personRepository->findByNamePaginate($search);

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('person/listAjax.html.twig', [
                'pagination' => $pagination
            ]);
            $found = count($pagination) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('person/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/person/team/{id}', name: 'team_players')]
    public function teamPlayers(
        int $id,
        Request $request,
        PersonRepository $personRepository,
        TeamRepository $teamRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $team = $teamRepository->find($id);
    
        if (!$team) {
            return $this->render('person/players.html.twig', [
                'teamExist' => false,
                'team' => null
            ]);
        }
        $teamId = $team->getId();
    
        $query = $personRepository->findPersonByTeam($teamId);
    
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );
    
        return $this->render('person/players.html.twig', [
            'team' => $team,
            'pagination' => $pagination,
            'teamExist' => true,
        ]);
    }

    #[Route('/person/new', name: 'new_person')]
    public function new(
        Request $request,
        PersonRepository $personRepository): Response
    {
        $edit = 0;
        $person = new Person();
        $personRepository->add($person);

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $person->getTeam();
            $hasTeacher = $personRepository->hayProfesorEnEquipo($team);

            if ($person->isTeacher() && $hasTeacher) {
                $this->addFlash('error', 'El equipo ya tiene un profesor');
                return $this->render('person/new.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Crear persona',
                    'titleForm' => 'Datos del nuevo miembro',
                    'edit' => $edit
                ]);
            }
            $personRepository->save();
            $this->addFlash('success', 'Persona creada correctamente');
            return $this->redirectToRoute('people');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear persona',
            'titleForm' => 'Datos del nuevo miembro',
            'edit' => $edit
        ]);
    }

    #[Route('/person/edit/{id}', name: 'person_edit')]
    final public function edit(
        Request $request,
        PersonRepository $personRepository,
        Person $person): Response
    {
        $edit = 1;
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $person->getTeam();
            $hasTeacher = $personRepository->hayProfesorEnEquipo($team);

            if ($person->isTeacher() && $hasTeacher) {
                $this->addFlash('error', 'El equipo ya tiene un profesor');
                return $this->render('person/new.html.twig', [
                    'form' => $form->createView(),
                    'person' => $person,
                    'title' => 'Editar persona',
                    'edit' => $edit
                ]);
            }

            $personRepository->save($person);
            $this->addFlash('updated', 'Persona actualizada correctamente');
            return $this->redirectToRoute('people');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
            'title' => 'Editar persona',
            'edit' => $edit
        ]);
    }

    #[Route('/person/delete/{id}', name: 'delete_person', methods: ['POST'])]
    public function delete(
        Request $request,
        PersonRepository $personRepository,
        Person $person
    ): JsonResponse
    {
        if ($request->isMethod('POST') && $request->getContent()) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['confirmar'])) {
                try {
                    $personRepository->remove($person, true);
                    return new JsonResponse(['status' => 'success', 'message' => 'Se ha borrado la persona correctamente']);
                } catch (\Exception $e) {
                    return new JsonResponse(['status' => 'error', 'message' => 'No se ha podido borrar'], 500);
                }
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Petición invalidad'], 400);
    }
}