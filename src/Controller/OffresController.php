<?php

namespace App\Controller;

use App\Services\Offre\OffresServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Traits\FilterType;

#[Route('offres')]
class OffresController extends AbstractController
{
    #[Route('', name: 'offres_index', methods: ['GET'])]
    public function index(OffresServices $offresServices): JsonResponse
    {
        $response =  $offresServices->getData(FilterType::BY_OTHER);;
        return $this->json($response, $response['status'], [], ['groups' => 'read:offre:list:user']);
    }

    #[Route('/user', name: 'offres_user_index', methods: ['GET'])]
    public function index_user(OffresServices $offresServices): JsonResponse
    {
        $response =  $offresServices->getData(FilterType::BY_USER);
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
