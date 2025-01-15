<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Identite\IdentiteServices;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('identite')]
class IdentiteController extends AbstractController
{

    #[Route('', name: 'identite_new', methods: ['POST'])]
    public function add(Request $request, IdentiteServices $identiteServices): JsonResponse
    {
        $response = $identiteServices->creer($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }

    #[Route('', name: 'identite_show', methods: ['GET'])]
    public function show(IdentiteServices $identiteServices): JsonResponse
    {
        $response = $identiteServices->detail();
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }

    #[Route('', name: 'identite_edit', methods: ['PUT'])]
    public function edit(Request $request, IdentiteServices $identiteServices): JsonResponse
    {
        $response = $identiteServices->modifier($request);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }

    #[Route('', name: 'identite_delete', methods: ['DELETE'])]
    public function delete(IdentiteServices $identiteServices): Response
    {
        $response = $identiteServices->supprimer();
        return $this->json($response, $response['status']);
    }
}
