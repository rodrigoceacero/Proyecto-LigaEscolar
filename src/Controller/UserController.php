<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'users')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = $request->query->get('search', '');
        $search = '%' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '%';
        $query = $userRepository->findByUsernamePaginate($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('user/listAjax.html.twig', [
                'pagination' => $pagination
            ]);
            $found = count($pagination) > 0;

            return $this->json([
                'content' => $content,
                'found' => $found
            ]);
        }

        return $this->render('user/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/user/new', name: 'new_user')]
    public function new(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $edit = 0;
        $user = new User();

        $userRepository->add($user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $user->getUsername();
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $pass . '123'
            ));
            $userRepository->save();
            $this->addFlash('success', 'Usuario creado correctamente');
            return $this->redirectToRoute('users');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crear usuario',
            'titleForm' => 'Datos del nuevo usuario',
            'edit' => $edit,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'user_edit')]
    final public function edit(
        Request $request,
        UserRepository $userRepository,
        User $user): Response
    {
        $edit = 1;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);
            $this->addFlash('updated', 'Usuario actualizado correctamente');
            return $this->redirectToRoute('users');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'title' => 'Editar usuario',
            'edit' => $edit,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['POST'])]
    public function delete(
        Request $request,
        UserRepository $userRepository,
        User $user
    ): JsonResponse
    {
        if ($request->isMethod('POST') && $request->getContent()) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['confirmar'])) {
                try {
                    $userRepository->remove($user, true);
                    return new JsonResponse(['status' => 'success', 'message' => 'Se ha borrado el usuario correctamente']);
                } catch (\Exception $e) {
                    return new JsonResponse(['status' => 'error', 'message' => 'No se ha podido borrar'], 500);
                }
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Petición invalidad'], 400);
    }
}