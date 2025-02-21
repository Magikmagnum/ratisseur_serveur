<?php

namespace App\Services\interfaces;

use Symfony\Component\HttpFoundation\Request;


/**
 * @template T of object
 */
interface ServiceListInterface
{
    public function liste(): array;
    public function listeUtilisateur(): array;
    public function detail(int $id): array;
    public function creer(Request $request): array;
    public function modifier(int $id, Request $request): array;
    public function supprimer(int $id): array;

    /**
     * @template T of object
     * @param T $entity Instance de l'entité à hydrater
     * @param array $data Le tableau des données à mapper
     * @return T L'entité hydratée
     */
    public function mapDataToEntity(object $entity, array $data): object;


    /**
     * Récupère une instance de l'entité associée à l'utilisateur connecté.
     *
     * @return T L'entité hydratée
     */
    public function getEntity(int $id = null): object;
}
