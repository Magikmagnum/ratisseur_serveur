<?php

namespace App\Services\Identite;

use Symfony\Component\HttpFoundation\Request;

interface IdentiteInterface
{
    public function detail(): array;
    public function supprimer(): array;
    public function creer(Request $request): array;
    public function modifier(Request $request): array;
}
