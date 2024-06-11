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

class PersonController extends AbstractController
{
    #[Route('/person', name: 'people')]
    public function index(
        Request $request,
        PersonRepository $personRepository
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $people = $personRepository->findByName($search);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('person/listAjax.html.twig', [
                'people' => $people
            ]);
            $found = count($people) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('person/list.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/person/team/{id}', name: 'team_players')]
    public function teamPlayers(
        int $id,
        PersonRepository $personRepository,
        TeamRepository $teamRepository
    ): Response
    {
        $team = $teamRepository->find($id);
        if (!$team) {
            throw $this->createNotFoundException('El equipo no existe'); /*devolver swAL.FIRE*/
        }
        $teamId = $team->getId();

        $players = $personRepository->findPersonByTeam($teamId);

        return $this->render('person/players.html.twig', [
            'team' => $team,
            'people' => $players,
        ]);
    }


    #[Route('/person/new', name: 'new_person')]
    public function new(
        Request $request,
        PersonRepository $personRepository): Response
    {
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
                    'titleForm' => 'Añadir datos'
                ]);
            }
            $personRepository->save();
            $this->addFlash('success', 'Persona creada correctamente');
            return $this->redirectToRoute('people');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear persona',
            'titleForm' => 'Añadir datos'
        ]);
    }

    #[Route('/person/edit/{id}', name: 'person_edit')]
    final public function edit(
        Request $request,
        PersonRepository $personRepository,
        Person $person): Response
    {
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
                    'titleForm' => 'Actualizar datos'
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
            'titleForm' => 'Actualizar datos'
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