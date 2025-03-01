<?php

namespace App\Controller;


use App\DTO\LocalisationDTO;
use App\Controller\AbstractController;
use App\Services\Localisation\LocalisationServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('localisation')]
class LocalisationController extends AbstractController
{
    #[Route('', name: 'localisation_index', methods: ['GET'])]
    public function index(LocalisationServices $localisationServices): JsonResponse
    {
        $response =  $localisationServices->liste();
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    #[Route('/user', name: 'localisation_user_index', methods: ['GET'])]
    public function index_user(LocalisationServices $localisationServices): JsonResponse
    {
        $response =  $localisationServices->listeUtilisateur();
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    #[Route('/{id}', name: 'localisation_show', methods: ['GET'])]
    public function show($id, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->detail($id);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('', name: 'localisation_new', methods: ['POST'])]
    public function add(Request $request, LocalisationServices $localisationServices): JsonResponse
    {        
        $response = $localisationServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('/{id}', name: 'localisation_edit', methods: ['POST'])]
    public function edit($id, Request $request, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->modifier($id, $request);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('/{id}', name: 'localisation_delete', methods: ['DELETE'])]
    public function delete($id, LocalisationServices $localisationServices): Response
    {
        $response = $localisationServices->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
