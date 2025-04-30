<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use App\Traits\FilterType;
use Doctrine\Common\Collections\Criteria;

/**
 * Interface pour la gestion des services de liste d'entités.
 *
 * Cette interface définit un ensemble de méthodes permettant d'effectuer des opérations
 * sur une collection d'entités, y compris la récupération avec filtres, la création,
 * la modification et la suppression.
 *
 * @template T of object
 */
interface ServiceListInterfaces
{
    /**
     * Récupère les détails d'une entité spécifique en fonction de son ID.
     *
     * @param int $id L'identifiant unique de l'entité.
     * @return array Un tableau contenant les détails de l'entité.
     */
    public function detail(int $id): array;

    /**
     * Crée une nouvelle entité à partir des données fournies dans la requête.
     *
     * @param Request $request La requête HTTP contenant les données de création.
     * @return array Un tableau contenant les informations de l'entité créée.
     */
    public function creer(Request $request): array;

    /**
     * Modifie une entité existante identifiée par son ID.
     *
     * @param int $id L'identifiant de l'entité à modifier.
     * @param Request $request La requête contenant les nouvelles données.
     * @return array Un tableau contenant les informations de l'entité mise à jour.
     */
    public function modifier(int $id, Request $request): array;

    /**
     * Supprime une entité spécifique en fonction de son ID.
     *
     * @param int $id L'identifiant unique de l'entité à supprimer.
     * @return array Un tableau indiquant le succès ou l'échec de la suppression.
     */
    public function supprimer(int $id): array;

    /**
     * Récupère une liste d'entités avec possibilité de filtrage.
     *
     * @param FilterType $type Type de filtre à appliquer (ex. tout récupérer, par catégorie, par statut...).
     * @param int|null $id ID optionnel permettant d'affiner la recherche.
     * @param array $criteria Tableau de critères supplémentaires à appliquer au filtre.
     * @return array Un tableau contenant la liste des entités filtrées.
     */
    public function getData(FilterType $type = FilterType::BY_ALL, ?int $id = null, ?array $criteria = []): array;

    /**
     * Récupère une instance d'une entité spécifique en fonction de l'ID fourni.
     *
     * Si aucun ID n'est fourni, l'implémentation peut décider de récupérer une entité
     * en fonction du contexte (ex. l'utilisateur connecté).
     *
     * @param int|null $id L'identifiant optionnel de l'entité à récupérer.
     * @return T L'entité correspondante.
     */
    public function getEntity(?int $id = null): object;

    /**
     * Hydrate une entité avec les données fournies.
     *
     * Cette méthode permet de mapper les données d'un tableau à une entité spécifique.
     *
     * @template T of object
     * @param T $entity Instance de l'entité à hydrater.
     * @param array $data Tableau contenant les données à mapper.
     * @return T L'entité hydratée.
     */
    public function mapDataToEntity(object $entity, array $data): object;
}
