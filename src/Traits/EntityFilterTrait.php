<?php

namespace App\Traits;

use App\Entity\User;
use App\Helpers\HttpResponseHelper;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;


enum FilterType: string
{
    case BY_USER = 'filter_by_user';
    case BY_OTHER = 'filter_by_other_user';
    case BY_ALL = 'filter_by_all';
    case BY_OTHER_ENTITY_ID = 'filter_by_entity_id';
}

/**
 * Trait permettant de récupérer des listes d'entités selon le contexte utilisateur.
 *
 * Ce trait fournit une méthode générique `getData()` qui retourne une liste d'entités
 * selon trois modes :
 * - `BY_USER` : Récupère uniquement les entités appartenant à l'utilisateur connecté.
 * - `BY_OTHER` : Récupère toutes les entités sauf celles de l'utilisateur connecté.
 * - `BY_OTHER_ENTITY_ID` : .
 * - `BY_ALL` : Récupère toutes les entités sans filtre.
 **/

trait EntityFilterTrait
{
    /**
     * Récupère les données en fonction du type spécifié.
     *
     * @param FilterType $type Le type de filtre a appliquer (`BY_USER`, `BY_OTHER`, `BY_ALL`).
     * @param ?int $id l'id de l'entity à filtrer
     * @return array Réponse HTTP contenant la liste des entités.
     * 
     * @throws ValidationException Si l'utilisateur n'est pas authentifié.
     */
    public function getData(FilterType $type = FilterType::BY_ALL, ?int $id = null): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->fetchData($type, $user, $id)
        );
    }

    /**
     * Récupère les entités selon le type de liste demandé.
     *
     * @param FilterType $type Type de données à récupérer.
     * @param User|null $user L'utilisateur connecté.
     * @param ?int $id l'id de l'entity à filtrer
     * @return array Liste des entités correspondantes.
     */
    private function fetchData(FilterType $type, ?User $user, ?int $id = null): array
    {
        $repository = $this->repository;

        return match ($type) {
            FilterType::BY_USER => $repository->findBy(['user' => $user]),
            FilterType::BY_OTHER => $repository->findAllExcepteUser($user),
            // Le repository de realisation, 
            FilterType::BY_OTHER_ENTITY_ID => $repository->findAllByOtherEntity($id),
            FilterType::BY_ALL => $repository->findAll(),
        };
    }
}