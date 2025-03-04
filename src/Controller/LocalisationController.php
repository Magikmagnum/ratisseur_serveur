<?php

namespace App\Controller;


use App\DTO\LocalisationDTO;
use App\Controller\AbstractController;
use App\Services\Localisation\LocalisationServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Traits\FilterType;


#[Route('localisation')]
class LocalisationController extends AbstractController
{
    #[Route('', name: 'localisation_index', methods: ['GET'])]
    public function index(LocalisationServices $localisationServices): JsonResponse
    {
        $response =  $localisationServices->getData(FilterType::BY_OTHER);;
        return $this->json($response, $response['status'], [], ['groups' => 'read:location:list']);
    }

    #[Route('/user', name: 'localisation_user_index', methods: ['GET'])]
    public function index_user(LocalisationServices $localisationServices): JsonResponse
    {
        $response =  $localisationServices->getData(FilterType::BY_USER);
        return $this->json($response, $response['status'], [], ['groups' => 'read:location:list']);
    }

    #[Route('/{id}', name: 'localisation_show', methods: ['GET'])]
    public function show($id, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->detail($id);
        return $this->json($response, $response["status"], [], ["groups" => "read:location:list"]);
    }

    #[Route('', name: 'localisation_new', methods: ['POST'])]
    public function add(Request $request, LocalisationServices $localisationServices): JsonResponse
    {        
        $response = $localisationServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:location:list"]);
    }

    #[Route('/{id}', name: 'localisation_edit', methods: ['PUT'])]
    public function edit($id, Request $request, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->modifier($id, $request);
        return $this->json($response, $response["status"], [], ["groups" => "read:location:list"]);
    }

    #[Route('/{id}', name: 'localisation_delete', methods: ['DELETE'])]
    public function delete($id, LocalisationServices $localisationServices): Response
    {
        $response = $localisationServices->supprimer($id);
        return $this->json($response, $response['status']);
    }
}
