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
    private RealisationsServices $services;
    const SERIALIZATION_LISTE = "read:realisation:list:user";
    const SERIALIZATION_SINGLE = "read:realisation:item";

    public function __construct(RealisationsServices $realisationsServices)
    {
        $this->services = $realisationsServices;
    }

    // Créer une réalisation : il faut l'ID de la compétence et les données de la réalisation
    #[Route('/{id}', name: 'realisations_new', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function add(int $id, Request $request, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $this->services->creer($request, $id);
        return $this->json($response, $response['status'], [], ['groups' => self::SERIALIZATION_LISTE]);
    }

    // Récupérer la liste des réalisations d'une compétence donnée
    #[Route('/{id}', name: 'realisations_index', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(int $id, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $this->services->getData(FilterType::BY_OTHER_ENTITY_ID, $id);
        return $this->json($response, $response['status'], [], ['groups' => self::SERIALIZATION_SINGLE]);
    }

    // Modifier une réalisation : il faut l'ID de la réalisation et les nouvelles données
    #[Route('/{id}', name: 'realisations_edit', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $this->services->modifier($id, $request);
        return $this->json($response, $response['status'], [], ['groups' => self::SERIALIZATION_LISTE]);
    }

    // Supprimer une réalisation : il faut l'ID de la réalisation
    #[Route('/{id}', name: 'realisations_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, RealisationsServices $realisationsServices): JsonResponse
    {
        $response = $this->services->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
