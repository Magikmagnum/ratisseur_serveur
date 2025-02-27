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

    #[Route('', name: 'localisation_new', methods: ['POST'])]
    public function add(Request $request, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:localisation:item"]);
    }

    #[Route('', name: 'localisation_show', methods: ['GET'])]
    public function show(LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->detail();
        return $this->json($response, $response["status"], [], ["groups" => "read:localisation:item"]);
    }

    #[Route('', name: 'localisation_edit', methods: ['PUT'])]
    public function edit(Request $request, LocalisationServices $localisationServices): JsonResponse
    {
        $response = $localisationServices->modifier($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:localisation:item"]);
    }

    #[Route('', name: 'localisation_delete', methods: ['DELETE'])]
    public function delete(LocalisationServices $localisationServices): Response
    {
        $response = $localisationServices->supprimer();
        return $this->json($response, $response['status']);
    }
}
