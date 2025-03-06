<?php

namespace App\Traits;

use App\Traits\EntityFilterTrait;
use App\Helpers\HttpResponseHelper;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait EntityCrudSingleTrait
{
    use EntityFilterTrait;
    
    public function creer(Request $request): array
    {
        $entity = $this->hydrateEntity($this->getEntity(), $request);
        $this->entityHelper->save($entity, true);
        return HttpResponseHelper::response(Response::HTTP_CREATED, $entity);
    }

    public function modifier(Request $request): array
    {
        $entity = $this->hydrateEntity($this->getEntity(), $request);
        $this->entityHelper->save($entity);
        return HttpResponseHelper::response(Response::HTTP_OK, $entity);
    }

    public function supprimer(): array
    {
        $entity = $this->getEntity();
        // Vérifier si la méthode beforeDeleteEntity existe et l'appeler
        if (method_exists($this, 'beforeDeleteEntity')) {
            $this->beforeDeleteEntity($entity);
        }
        $this->entityHelper->delete($entity);
        return HttpResponseHelper::response(Response::HTTP_OK);
    }

    public function detail(): array
    {
        if (!$this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(Response::HTTP_OK, $this->getEntity());
    }
}
