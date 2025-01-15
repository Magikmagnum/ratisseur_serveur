<?php

namespace App\Services\Adresse;

use Symfony\Component\HttpFoundation\Request;

interface AdresseInterface
{
    public function detail(): array;
    public function supprimer(): array;
    public function creer(Request $request): array;
    public function modifier(Request $request): array;
}
