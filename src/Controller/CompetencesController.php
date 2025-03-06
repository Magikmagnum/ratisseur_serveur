<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Competence\CompetencesServices;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Traits\FilterType;

#[Route('competences')]
class CompetencesController extends AbstractController
{
    private CompetencesServices $services;
    const SERIALIZATION_LISTE = "read:competence:list:user";
    const SERIALIZATION_SINGLE = "read:competence:item";

    public function __construct(CompetencesServices $competencesServices)
    {
        $this->services = $competencesServices;
    }

    #[Route('', name: 'competences_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $response =  $this->services->getData(FilterType::BY_OTHER);
        return $this->json( $response, $response['status'], [], ['groups' => self::SERIALIZATION_LISTE]);
    }

    #[Route('/user', name: 'competences_user_index', methods: ['GET'])]
    public function index_user(): JsonResponse
    {
        $response =  $this->services->getData(FilterType::BY_USER);
        return $this->json($response, $response['status'], [], ['groups' => self::SERIALIZATION_LISTE]);
    }

    #[Route('/{id}', name: 'competences_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $response = $this->services->detail($id);
        return $this->json($response, $response["status"], [], ["groups" => self::SERIALIZATION_SINGLE]);
    }

    #[Route('', name: 'competences_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $response = $this->services->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => self::SERIALIZATION_SINGLE]);
    }

    #[Route('/{id}', name: 'competences_edit', methods: ['POST'])]
    public function edit($id, Request $request): JsonResponse
    {
        $response = $this->services->modifier($id, $request);
        return $this->json($response, $response["status"], [], ["groups" => self::SERIALIZATION_SINGLE]);
    }

    #[Route('/{id}', name: 'competences_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        $response = $this->services->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
