<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class HttpResponseHelper
{
    /**
     * return a response type array
     *  gestion des erreurs HTTP responses
     *
     * @param int $statusCode
     * @param array | object $data une instance de l'entity
     * @param string|null $message
     * @return 
     */
    public static function response(int $statusCode, $data = [], string $message = null)
    {
        switch ($statusCode) {

            case Response::HTTP_CREATED:

                $message === null && $message = "Ressource créee avec succès";
                return self::formatResponse(true, $statusCode, $data, $message);

            case Response::HTTP_OK:

                $message === null && $message = "Operation reussie";
                return self::formatResponse(true, $statusCode, $data, $message);

            case Response::HTTP_ACCEPTED:
                $message === null && $message = "La demande a été acceptée pour traitement";
                return self::formatResponse(true, $statusCode, $data, $message);

            case Response::HTTP_NO_CONTENT:
                $message === null && $message = "Pas de contenu à renvoyer";
                return self::formatResponse(true, $statusCode, $data, $message);

            case Response::HTTP_BAD_REQUEST:

                $message === null && $message = "Requète invalide";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_UNAUTHORIZED:

                $message === null && $message = "Impossible de vous authentifier, veuillez vous connecter";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_FORBIDDEN:

                $message === null && $message = "Vous n'avez pas les droits requis pour continuer cette action";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_FOUND:

                $message === null && $message = "Route ou ressource inexistante, vérifier le lien de la requête";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_MODIFIED:

                $message === null && $message = "Ressource non modifier";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_METHOD_NOT_ALLOWED:
                $message === null && $message = "Méthode non autorisée pour cette ressource";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_CONFLICT:
                $message === null && $message = "Conflit lors du traitement de la demande";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_PRECONDITION_FAILED:
                $message === null && $message = "La condition préalable à la demande a échoué";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_UNSUPPORTED_MEDIA_TYPE:
                $message === null && $message = "Type de média non supporté pour cette ressource";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $message === null && $message = "Erreur interne du serveur";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_SERVICE_UNAVAILABLE:
                $message === null && $message = "Service non disponible, veuillez réessayer plus tard";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_ACCEPTABLE:
                $message === null && $message = "Le serveur ne peut pas produire une réponse conforme aux types acceptés indiqués dans l'en-tête de la demande";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_REQUEST_TIMEOUT:
                $message === null && $message = "La demande a expiré ou le serveur a pris trop de temps à répondre";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_GONE:
                $message === null && $message = "La ressource demandée n'est plus disponible et aucune redirection n'est connue";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_LENGTH_REQUIRED:
                $message === null && $message = "La longueur de la demande requise n'a pas été spécifiée";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_REQUEST_ENTITY_TOO_LARGE:
                $message === null && $message = "La taille de la demande est trop grande pour être traitée par le serveur";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_UNPROCESSABLE_ENTITY:
                $message === null && $message = "La requète demande est invalide ou a des champs manquants";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_LOCKED:
                $message === null && $message = "La ressource est verrouillée, vous ne pouvez pas effectuer cette opération actuellement";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_FAILED_DEPENDENCY:
                $message === null && $message = "L'opération a échoué en raison d'une dépendance non satisfaite";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_UPGRADE_REQUIRED:
                $message === null && $message = "La mise à niveau du client est nécessaire pour traiter la demande";
                return self::formatResponse(false, $statusCode, $data, $message);

            case Response::HTTP_PRECONDITION_REQUIRED:
                $message === null && $message = "La condition préalable à la demande est requise et non satisfaite";
                return self::formatResponse(false, $statusCode, $data, $message);

                // Ajoutez d'autres cas selon vos besoins

            default:
                // Cas par défaut pour d'autres codes de statut non gérés
                $message === null && $message = "Code de statut non géré";
                return self::formatResponse(false, $statusCode, $data, $message);
        }
    }

    private static function formatResponse($success, $statusCode, $data = [], $message = null)
    {
        $response = ["status" => $statusCode, "success" => $success];
        $data ? $response["data"] = $data : null;
        $message ? $response["message"] = $message : null;
        return $response;
    }
}
