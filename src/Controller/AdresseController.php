<?php

namespace App\Controller;


use App\DTO\AdresseDTO;
use App\Controller\AbstractController;
use App\Services\Adresse\AdresseServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('adresse')]
class AdresseController extends AbstractController
{

    #[Route('', name: 'adresse_new', methods: ['POST'])]
    public function add(Request $request, AdresseServices $adresseServices): JsonResponse
    {
        $response = $adresseServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:adresse:item"]);
    }

    #[Route('', name: 'adresse_show', methods: ['GET'])]
    public function show(AdresseServices $adresseServices): JsonResponse
    {
        $response = $adresseServices->detail();
        return $this->json($response, $response["status"], [], ["groups" => "read:adresse:item"]);
    }

    #[Route('', name: 'adresse_edit', methods: ['PUT'])]
    public function edit(Request $request, AdresseServices $adresseServices): JsonResponse
    {
        $response = $adresseServices->modifier($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:adresse:item"]);
    }

    #[Route('', name: 'adresse_delete', methods: ['DELETE'])]
    public function delete(AdresseServices $adresseServices): Response
    {
        $response = $adresseServices->supprimer();
        return $this->json($response, $response['status']);
    }
}
