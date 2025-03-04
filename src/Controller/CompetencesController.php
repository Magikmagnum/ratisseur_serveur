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
    #[Route('', name: 'competences_index', methods: ['GET'])]
    public function index(CompetencesServices $competencesServices): JsonResponse
    {
        $response =  $competencesServices->getData(FilterType::BY_OTHER);
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    #[Route('/user', name: 'competences_user_index', methods: ['GET'])]
    public function index_user(CompetencesServices $competencesServices): JsonResponse
    {
        $response =  $competencesServices->getData(FilterType::BY_USER);
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    #[Route('/{id}', name: 'competences_show', methods: ['GET'])]
    public function show($id, CompetencesServices $competencesServices): JsonResponse
    {
        $response = $competencesServices->detail($id);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('', name: 'competences_new', methods: ['POST'])]
    public function add(Request $request, CompetencesServices $competencesServices): JsonResponse
    {
        $response = $competencesServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('/{id}', name: 'competences_edit', methods: ['POST'])]
    public function edit($id, Request $request, CompetencesServices $competencesServices): JsonResponse
    {
        $response = $competencesServices->modifier($id, $request);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('/{id}', name: 'competences_delete', methods: ['DELETE'])]
    public function delete($id, CompetencesServices $competencesServices): Response
    {
        $response = $competencesServices->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
