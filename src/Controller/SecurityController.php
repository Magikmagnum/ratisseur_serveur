<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    #[Route('/register', name: 'security_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($data['password']);

        if ($response = $this->validateEntity($user)) {
            return $this->json($response, $response["status"]);
        }

        $encodedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        $entityManager = $this->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_CREATED, $user);

        return $this->json($response, $response["status"], [], ["groups" => "read:auth:item"]);
    }

    #[Route('/users/update', name: 'security_reset', methods: ['PUT'])]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent());

        /** @var UserInterface $user */
        $user = $this->getUser();

        $userForCheck = new User();
        $userForCheck->setEmail($data->newemail ?? $user->getEmail());
        $userForCheck->setPassword($data->newpassword ?? $user->getPassword());
        $userForCheck->setRoles(['ROLE_USER']);

        if ($response = $this->validateEntity($userForCheck)) {
            return $this->json($response, $response["status"]);
        }

        if (isset($data->newemail)) {
            $user->setEmail($data->newemail);
        }

        if (isset($data->newpassword)) {
            $user->setPassword($passwordHasher->hashPassword($user, $data->newpassword));
        }

        $this->getManager()->persist($user);
        $this->getManager()->flush();

        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_OK, $user);

        return $this->json($response, $response["status"], [], ["groups" => "read:auth:item"]);
    }

    #[Route('/users/recovery', name: 'security_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['username'];
        $password = $data['password'];
        $repassword = $data['repassword'];

        if ($password !== $repassword) {
            $response = $this->statusCode(Response::HTTP_UNPROCESSABLE_ENTITY, ['message' => 'Les mots de passe ne correspondent pas.']);
            return $this->json($response, $response['status']);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND, ['message' => 'Utilisateur non trouvé.']);
            return $this->json($response, $response['status']);
        }

        $user->setPassword($password);
        if ($response = $this->validateEntity($user)) {
            return $this->json($response, $response['status']);
        }

        $hashPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashPassword);

        $this->getManager()->flush();

        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_OK, $user);

        return $this->json($response, $response['status'], [], ["groups" => "read:auth:item"]);
    }

    #[Route('/users/{id}', name: 'security_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);
        $password = $data['password'];

        $user = $userRepository->find($id);

        if (!$user) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND, ['message' => 'Utilisateur non trouvé.']);
            return $this->json($response, $response['status']);
        }

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $response = $this->statusCode(Response::HTTP_UNAUTHORIZED, ['message' => 'Mot de passe incorrect.']);
            return $this->json($response, $response['status']);
        }

        $currentUser = $this->getUser();
        if ($user->getId() !== $currentUser->getId()) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet utilisateur.');
        }

        $entityManager = $this->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK, ['message' => 'Utilisateur supprimé avec succès.']);
        return $this->json($response, $response['status']);
    }

    #[Route('/users', name: 'list_users', methods: ['GET'])]
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $users);
        return $this->json($response, $response["status"], [], ["groups" => "read:auth:list"]);
    }
}
