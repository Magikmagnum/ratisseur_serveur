<?php

namespace App\Exception;

/**
 * Exception personnalisée pour gérer les erreurs d'hydratation d'entités.
 */
class HydrationException extends \Exception
{
    /**
     * @var string Détails des erreurs d'hydratation.
     */
    protected string $hydratationErrors;

    /**
     * HydrationException constructor.
     *
     * @param string $hydratationErrors Erreurs d'hydratation.
     * @param int $code Code de l'exception (par défaut : 0).
     * @param \Throwable|null $previous Exception précédente (optionnelle).
     */
    public function __construct(string $hydratationErrors, int $code = 0, ?\Throwable $previous = null)
    {
        $this->hydratationErrors = $hydratationErrors;
        $message = 'Hydratation failed: ' . $hydratationErrors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Récupère les erreurs d'hydratation.
     *
     * @return string Erreurs d'hydratation.
     */
    public function getErrors(): string
    {
        return $this->hydratationErrors;
    }
}