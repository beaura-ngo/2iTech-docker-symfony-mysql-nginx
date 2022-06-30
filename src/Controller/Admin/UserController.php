<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAddFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/users', name: 'admin_users_')]
class UserController extends AbstractController {


    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepo): Response {

        $users = $userRepo->findAll();
        return $this->render('admin/users/index.html.twig',
    compact('users'));
    }

    #[Route('/add', name: 'add')]
    public function addUser(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new User;
        $form = $this->createForm(UserAddFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash('success', 'User has been succesfully added');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render(
            'admin/users/crud/addUser.html.twig',
            ['form' => $form->createView()]
        );
    }

    #[Route('/edit/{id}', name:'edit')]
    public function updateUser(Request $request,ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id' . $id
            );
        }
        $form = $this->createForm(UserAddFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash('success', 'User : ' . $user->getEmail() . ' has been edited');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render(
            'admin/users/crud/addUser.html.twig',
            ['user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route(':delete/{id}', name: 'delete')]
    public function deleteUser(ManagerRegistry $doctrine, User $user): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'User : ' . $user->getEmail() . ' has been deleted');

        return $this->redirectToRoute('admin_users_index');
    }
}