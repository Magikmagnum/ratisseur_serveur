<?php

namespace App\Traits;

use App\Exception\HydrationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * 
 * Gansa Diambote eric 
 * Le 17 janvier 2025
 * 
 * Documentation pour le trait EntityHydratorTrait
 * 
 * Le trait EntityHydratorTrait fournit un ensemble d’outils pour hydrater des entités à partir des données extraites 
 * d’une requête HTTP. 
 * Il inclut des méthodes pour traiter les données JSON, les données de formulaire et les fichiers téléchargés.
 * 
 * Ce trait est conçu pour être réutilisable dans différentes classes et offre une séparation claire 
 * des responsabilités entre l’extraction des données et leur mapping à des entités spécifiques.
 * 
 * Utilisation de `mapDataToEntity` :
 * 
 * La méthode `mapDataToEntity` est au cœur du processus d’hydratation. 
 * Cette méthode est responsable de mapper les données extraites (JSON, formulaire, fichiers) à une entité.
 * 
 * Pourquoi utiliser `mapDataToEntity` :
 * 1. **Flexibilité** : Permet de personnaliser la façon dont les données sont assignées aux propriétés de l’entité.
 * 2. **Cohérence** : Centralise la logique d’hydratation, réduisant la duplication de code.
 * 3. **Réutilisabilité** : Chaque classe peut implémenter cette méthode en fonction des besoins spécifiques de ses entités.
 * 
 * 
 * Exemple d’implémentation pour une entité `User` :
 * 
 * ```php
 * protected function mapDataToEntity(User $entity, array $data): User
 * {
 *     $entity->setName($data['name'] ?? null);
 *     return $entity;
 * }
 * ```
 * 
 * Points importants :
 * - `mapDataToEntity` doit être implémentée par chaque classe qui utilise ce trait.
 * - Elle doit adapter les données à la structure de l’entité cible en prenant en compte les types et les validations.
 */
trait EntityHydratorTrait
{
    /**
     * Types MIME autorisés pour les fichiers téléchargés.
     */
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

    /**
     * Taille maximale autorisée pour les fichiers (en octets).
     */
    private const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB


    /**
     * @template T of object
     * @param T $entity Instance de l'entité à hydrater
     * @param Request $request La requête contenant les données
     * @return T Entité hydratée
     */
    public function hydrateEntity(object $entity, Request $request): object
    {
        return $this->hydrateCatchError(
            function () use ($entity, $request) {
                $data = $this->parseRequest($request);
                return $this->mapDataToEntity($entity, $data);
            }
        );
    }


    /**
     * Parse le payload JSON de la requête.
     *
     * @param Request $request La requête HTTP
     * @return array Le tableau des données extraites
     */
    public function parseRequest(Request $request): array
    {
        // Données JSON
        $jsonPayload = $this->getJsonPayload($request);

        // Données envoyées via `form-data` ou `x-www-form-urlencoded`
        $formData = $this->getFormData($request);

        // Fichiers téléchargés
        $uploadedFiles = $this->getUploadedFiles($request);

        // Fusionner les données
        return array_merge($jsonPayload, $formData, ['files' => $uploadedFiles]);
    }

    /**
     * Extrait les données des formulaires envoyées via `form-data` ou `x-www-form-urlencoded`.
     */
    private function getFormData(Request $request): array
    {
        return $request->request->all();
    }


    /**
     * Extrait le payload JSON de la requête.
     */
    private function getJsonPayload(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        if ($data) {
            /**
             * Vérifie que le JSON est valide.
             */
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new HydrationException('Invalid JSON: ' . json_last_error_msg());
            }

            /**
             * Vérifie que le JSON est bien un tableau.
             */
            if (!is_array($data)) {
                throw new HydrationException('Invalid JSON structure: Expected an array.');
            }
        }

        return $data ?? [];
    }

    /**
     * Extrait et traite les fichiers téléchargés depuis la requête.
     */
    private function getUploadedFiles(Request $request): array
    {
        $uploadedFiles = $request->files->all();
        $filesData = [];

        foreach ($uploadedFiles as $key => $file) {

            /**
             * Vérifie que le fichier est bien une instance de `UploadedFile`.
             */
            if (!$file instanceof UploadedFile) {
                throw new HydrationException('Invalid file uploaded for key: ' . $key);
            }

            /**
             * Vérifie que le fichier n'excède pas une certaine taille.
             */
            if ($file->getSize() > self::MAX_FILE_SIZE) { // 2MB
                throw new HydrationException(
                    'The file "' . $file->getClientOriginalName() . '" exceeds the maximum allowed size of ' . self::MAX_FILE_SIZE . '.'
                );
            }

            /**
             * Vérifie que le type MIME est autorisé.
             */
            if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
                throw new HydrationException(
                    'The file "' . $file->getClientOriginalName() . '" has an unsupported MIME type: ' . $file->getMimeType()
                );
            }

            $filesData[$key] = $file;
        }

        return $filesData;
    }


    /**
     * Hydrate une entité avec des données extraites de la requête.
     *
     * @template T of object
     * @param callable(T, array): T $mapper Fonction pour mapper les données à l'entité
     * @return T Entité hydratée
     */
    private function hydrateCatchError(callable $mapper): object
    {
        try {
            // Mapper les données à l'entité
            return $mapper();
        } catch (\Throwable $e) {
            throw new HydrationException("Error during hydration: " . $e->getMessage(), 0, $e);
        }
    }
}
