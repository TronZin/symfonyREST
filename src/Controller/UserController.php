<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    #[Route('/projects', name: 'project_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $users = $doctrine
            ->getRepository(User::class)
            ->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'gender' => $user->getGender(),
            ];
        }

        return $this->json($data);
    }


    #[Route('/projects', name: 'project_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setGender($request->request->get('gender'));

        $entityManager->persist($user);
        $entityManager->flush();

        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'gender' => $user->getGender(),
        ];

        return $this->json($data);
    }


    #[Route('/projects/{id}', name: 'project_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        if (!$user) {

            return $this->json('User not found for id ' . $id, 404);
        }

        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'gender' => $user->getGender(),
        ];

        return $this->json($data);
    }

    #[Route('/projects/{id}', name: 'project_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json('User not found for id' . $id, 404);
        }

        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setGender($request->request->get('gender'));
        $entityManager->flush();

        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'gender' => $user->getGender(),
        ];

        return $this->json($data);
    }

    #[Route('/projects/{id}', name: 'project_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json('User not found for id' . $id, 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json('User deleted successfully with id ' . $id);
    }
}