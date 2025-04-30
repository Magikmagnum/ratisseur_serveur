<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface générique pour la gestion des services applicatifs.
 * 
 * Cette interface définit un contrat standard pour la gestion des entités 
 * avec des méthodes permettant d'afficher, créer, modifier et supprimer des ressources.
 *
 * @template T of object
 */
interface ServiceInterfaces
{
    /**
     * Récupère les détails d'une entité.
     *
     * @return array Un tableau contenant les détails de l'entité.
     */
    public function detail(): array;

    /**
     * Crée une nouvelle entité à partir des données de la requête.
     *
     * @param Request $request La requête contenant les données de création.
     * @return array Un tableau contenant les informations de l'entité créée.
     */
    public function creer(Request $request): array;

    /**
     * Modifie une entité existante avec les données de la requête.
     *
     * @param Request $request La requête contenant les données de modification.
     * @return array Un tableau contenant les informations de l'entité mise à jour.
     */
    public function modifier(Request $request): array;

    /**
     * Supprime une entité.
     *
     * @return array Un tableau indiquant le succès ou l'échec de la suppression.
     */
    public function supprimer(): array;

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

    /**
     * Récupère une instance de l'entité associée à l'utilisateur connecté.
     *
     * Cette méthode est utile lorsque l'on travaille avec des entités liées à un contexte utilisateur.
     *
     * @return T L'entité correspondante à l'utilisateur.
     */
    public function getEntity(): object;
}