<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;

trait EntityHydratorTrait
{
    /**
     * @template T of object
     * @param T $entity Instance de l'entité à hydrater
     * @param Request $request La requête contenant les données
     * @return T Entité hydratée
     */
    private function hydrateEntity(object $entity, Request $request): object
    {
        return $this->entityHelper->hydrateCatchError(
            function () use ($entity, $request) {
                $data = $this->entityHelper->parseJsonPayload($request);
                return $this->mapDataToEntity(
                    $entity,
                    $data
                );
            }
        );
    }
}
