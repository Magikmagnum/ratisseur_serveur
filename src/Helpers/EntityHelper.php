<?php

namespace App\Helpers;

use App\Exception\HydrationException;
use App\Exception\ValidationException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class EntityHelper
{
    protected ValidatorInterface $validator;
    protected EntityManagerInterface $entityManager;
    protected Security $security;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security)
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * Valide une entité en fonction de ses contraintes de validation.
     *
     * @param object $entity L'entité à valider.
     * @param array|null $existingErrors Les erreurs déjà collectées, le cas échéant.
     * @return array|false Un tableau d'erreurs ou false s'il n'y a pas d'erreurs.
     */
    public function validate(object $entity, ?array $existingErrors = []): array|false
    {
        $validationResults = $this->validator->validate($entity);

        if (count($validationResults) > 0) {
            $validationErrors = array_map(static function ($violation) {
                return [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }, iterator_to_array($validationResults));

            $allErrors = $existingErrors ? array_merge($existingErrors, $validationErrors) : $validationErrors;

            return $this->buildErrorResponse(Response::HTTP_BAD_REQUEST, $allErrors);
        }

        return $existingErrors ? $this->buildErrorResponse(Response::HTTP_BAD_REQUEST, $existingErrors) : [];
    }

    /**
     * Enregistre ou met à jour une entité dans la base de données.
     *
     * @param object $entity L'entité à sauvegarder.
     * @param bool $isNew Indique si l'entité est nouvelle (true pour persist, false par defaut).
     * @param bool $isValidation Indique si l'entité doit etre valider avant la parsistance (false par defaut).
     * @return bool
     */
    public function save(object $entity, bool $isNew = false, bool $isValidation = false): bool
    {
        // Vérification des droits d'accès
        if ($isValidation && $isNew && !$this->isGranted('EDIT', $entity)) {
            throw new ValidationException(
                ['message' => 'Access denied for editing this entity.'],
                Response::HTTP_FORBIDDEN
            );
        }


        if ($isValidation && $validationErrors = $this->validate($entity)) {
            throw new ValidationException(
                $validationErrors,
                Response::HTTP_BAD_REQUEST
            );
        }

        $manager = $this->entityManager;

        if ($isNew) {
            $manager->persist($entity);
        }

        $manager->flush();
        return true;
    }

    /**
     * Supprime une entité de la base de données.
     *
     * @param object $entity L'entité à supprimer.
     * @param bool $isValidation Indique si l'entité doit etre valider avant la parsistance (false par defaut).
     * @return bool
     */
    public function delete(object $entity, bool $isValidation = false): bool
    {
        // Vérification des droits d'accès
        if ($isValidation && !$this->isGranted('DELETE', $entity)) {
            throw new ValidationException(
                ['message' => 'Access denied for deleting this entity.'],
                Response::HTTP_FORBIDDEN
            );
        }

        $manager = $this->entityManager;
        $manager->remove($entity);
        $manager->flush();
        return true;
    }

    /**
     * Récupère le gestionnaire d'entités.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Construit une réponse d'erreur.
     *
     * @param int $statusCode Code HTTP.
     * @param array $errors Liste des erreurs.
     * @return array Structure de la réponse.
     */
    private function buildErrorResponse(int $statusCode, array $errors): array
    {
        return [
            'status' => $statusCode,
            'success' => false,
            'errors' => $errors,
        ];
    }

    /**
     * Vérifie si l'utilisateur actuel dispose de l'autorisation requise.
     *
     * @param string $attribute L'attribut de sécurité (par exemple, 'EDIT', 'DELETE').
     * @param mixed $subject L'objet ou la ressource pour laquelle les droits sont vérifiés.
     * @throws AccessDeniedException Si l'accès est refusé.
     */
    private function isGranted(string $attribute, $subject): bool
    {
        return $this->security->isGranted($attribute, $subject);
    }

    /**
     * Hydrate une entité avec des données extraites de la requête.
     *
     * @template T of object
     * @param callable(T, array): T $mapper Fonction pour mapper les données à l'entité
     * @return T Entité hydratée
     */
    public function hydrateCatchError(callable $mapper): object
    {
        try {
            // Mapper les données à l'entité
            return $mapper();
        } catch (\Throwable $e) {
            throw new HydrationException("Error during hydration: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Parse le payload JSON de la requête.
     *
     * @param Request $request La requête HTTP
     * @return array Le tableau des données extraites
     */
    public function parseJson(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        /**
         * Cette vérification rend votre code robuste 
         * et prévient des erreurs silencieuses difficiles à diagnostiquer. 
         * Elle s’assure également que seuls des JSON bien formés sont traités.
         */
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HydrationException('Invalid JSON: ' . json_last_error_msg());
        }

        return $data ?? [];
    }



    /**
     * Parse le payload JSON de la requête.
     *
     * @param Request $request La requête HTTP
     * @param array $criteria le tableau des index des fichiers à recuperer
     * @return array Le tableau des données extraites
     */
    public function parseFromData(Request $request, array $criteria): array
    {
        $data = $request->request->all();
        foreach ($criteria as $key) {
            if (!$request->files->has($key)) {
                throw new HydrationException("Missing file for key '$key'");
            }
            $data[$key] = $request->files->get($key);
        }

        return $data ?? [];
    }
}
