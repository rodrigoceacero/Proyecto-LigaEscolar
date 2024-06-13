<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Form\SportType;
use App\Repository\SportRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportController extends AbstractController
{
    #[Route('/sport', name: 'sports')]
    public function index(
        Request $request,
        SportRepository $sportRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $search = $request->query->get('search', '');
        $inactive = $request->query->get('inactive', 'false') === 'true';
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';

        $query = $sportRepository->findByNamePagination($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('sport/listAjax.html.twig', [
                'pagination' => $pagination,
            ]);
            $found = count($pagination) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('sport/list.html.twig', [
            'pagination' => $pagination,
            'inactive' => $inactive,
        ]);
    }

    #[Route('/sport/new', name: 'new_sport')]
    public function new(
        Request $request,
        SportRepository $sportRepository): Response
    {
        $edit = 0;
        $sport= new Sport();
        $sportRepository->add($sport);

        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sportRepository->save();
            $this->addFlash('success', 'Deporte creado correctamente');
            return $this->redirectToRoute('sports');
        }

        return $this->render('sport/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear deporte',
            'titleForm' => 'Añadir datos',
            'edit' => $edit
        ]);
    }

    #[Route('/sport/edit/{id}', name: 'sport_edit')]
    final public function edit(
        Request $request,
        SportRepository $sportRepository,
        Sport $sport): Response
    {
        $edit = 1;
        $form = $this->createForm(SportType::class, $sport);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sportRepository->save();
            $this->addFlash('updated', 'Deporte actualizado correctamente');
            return $this->redirectToRoute('sports');
        }

        return $this->render('sport/new.html.twig', [
            'form' => $form->createView(),
            'sport' => $sport,
            'title' => 'Editar deporte',
            'titleForm' => 'Actualizar datos',
            'edit' => $edit
        ]);
    }
}