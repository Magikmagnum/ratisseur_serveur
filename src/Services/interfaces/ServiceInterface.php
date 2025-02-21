<?php

namespace App\Services\interfaces;

use Symfony\Component\HttpFoundation\Request;


/**
 * @template T of object
 */
interface ServiceInterface
{
    public function detail(): array;
    public function creer(Request $request): array;
    public function modifier(Request $request): array;
    public function supprimer(): array;

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
    public function getEntity(): object;
}
