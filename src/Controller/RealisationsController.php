<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Realisation\RealisationsServices;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Traits\FilterType;

#[Route('realisations')]
class RealisationsController extends AbstractController
{
    // Créer une réalisation : il faut l'ID de la compétence et les données de la réalisation
    #[Route('/{id}', name: 'realisations_new', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function add(int $id, Request $request, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $realisationsServices->creer($request, $id);
        return $this->json($response, $response['status'], [], ['groups' => 'read:realisation:item']);
    }

    // Récupérer la liste des réalisations d'une compétence donnée
    #[Route('/{id}', name: 'realisations_index', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(int $id, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $realisationsServices->getData(FilterType::BY_OTHER_ENTITY_ID, $id);
        return $this->json($response, $response['status'], [], ['groups' => 'read:realisation:list:user']);
    }

    // Modifier une réalisation : il faut l'ID de la réalisation et les nouvelles données
    #[Route('/{id}', name: 'realisations_edit', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $realisationsServices->modifier($id, $request);
        return $this->json($response, $response['status'], [], ['groups' => 'read:realisation:item']);
    }

    // Supprimer une réalisation : il faut l'ID de la réalisation
    #[Route('/{id}', name: 'realisations_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $realisationsServices->supprimer($id);
        return $this->json($response, $response['status']);
    }

    // #[Route('/user', name: 'realisations_user_index', methods: ['GET'])]
    // public function index_user(RealisationsServices $realisationsServices): JsonResponse
    // {
    //     $response =  $realisationsServices->listeUtilisateur();
    //     return $this->json($response, $response['status'], [], ['groups' => 'read:realisation:list:user']);
    // }

    // #[Route('/{id}', name: 'realisations_show', methods: ['GET'])]
    // public function show($id, RealisationsServices $realisationsServices): JsonResponse
    // {
    //     $response = $realisationsServices->detail($id);
    //     return $this->json($response, $response["status"], [], ["groups" => "read:realisation:item"]);
    // }
}
