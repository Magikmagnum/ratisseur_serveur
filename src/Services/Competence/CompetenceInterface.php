<?php

namespace App\Services\Competence;

use Symfony\Component\HttpFoundation\Request;

interface CompetenceInterface
{
    public function liste(): array;
    public function listeUtilisateur(): array;
    public function detail(int $id): array;
    public function creer(Request $request): array;
    public function modifier(int $id, Request $request): array;
    public function supprimer(int $id): array;

    // /**
    //  * @template T of object
    //  * @param T $entity Instance de l'entité à hydrater
    //  * @param array $data Le tableau des données à mapper
    //  * @return T L'entité hydratée
    //  */
    // public function mapDataToEntity(object $entity, array $data): object;
}
