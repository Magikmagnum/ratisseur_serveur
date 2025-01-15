<?php

namespace App\Services\User;

use Symfony\Component\HttpFoundation\Request;

interface UserInterface
{
    public function detailUtilisateur(int $id): array;
    public function creerUnUtilisateur(Request $request): array;
    public function modifierUnUtilisateur(int $id, Request $request): array;
    public function supprimerUnUtilisateur(int $id): array;
}
