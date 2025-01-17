<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\Offre\OffresServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('offres')]
class OffresController extends AbstractController
{
    #[Route('', name: 'offres_index', methods: ['GET'])]
    public function index(OffresServices $offresServices): JsonResponse
    {
        $response =  $offresServices->liste();
        return $this->json($response, $response['status'], [], ['groups' => 'read:offre:list:user']);
    }

    #[Route('/user', name: 'offres_user_index', methods: ['GET'])]
    public function index_user(OffresServices $offresServices): JsonResponse
    {
        $response =  $offresServices->listeUtilisateur();
        return $this->json($response, $response['status'], [], ['groups' => 'read:offre:list:user']);
    }

    #[Route('/{id}', name: 'offres_show', methods: ['GET'])]
    public function show($id, OffresServices $offresServices): JsonResponse
    {
        $response = $offresServices->detail($id);
        return $this->json($response, $response["status"], [], ["groups" => "read:offre:item"]);
    }

    #[Route('', name: 'offres_new', methods: ['POST'])]
    public function add(Request $request, OffresServices $offresServices): JsonResponse
    {
        $response = $offresServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:offre:item"]);
    }

    #[Route('/{id}', name: 'offres_edit', methods: ['POST'])]
    public function edit($id, Request $request, OffresServices $offresServices): JsonResponse
    {
        $response = $offresServices->modifier($id, $request);
        return $this->json($response, $response["status"], [], ["groups" => "read:offre:item"]);
    }

    #[Route('/{id}', name: 'offres_delete', methods: ['DELETE'])]
    public function delete($id, OffresServices $offresServices): Response
    {
        $response = $offresServices->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
