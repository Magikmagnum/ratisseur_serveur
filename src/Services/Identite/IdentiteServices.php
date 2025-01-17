<?php

namespace App\Services\Identite;

use App\Entity\Identite;
use App\Helpers\EntityHelper;
use App\Traits\EntityCrudSingleTrait;
use App\Traits\EntityHydratorTrait;
use App\Services\Identite\IdentiteInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IdentiteServices extends AbstractController implements IdentiteInterface
{
    use EntityHydratorTrait;
    use EntityCrudSingleTrait;

    protected EntityHelper $entityHelper;

    public function __construct(EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
    }

    /**
     * @param Identite $identite
     * @param array $data
     * @return Identite
     */
    private function mapDataToEntity(Identite $identite, array $data): Identite
    {
        // Assurez-vous que l'utilisateur est défini
        if (!$identite->getUser()) {
            $identite->setUser($this->getUser());
        }

        // Vérifie et assigne le nom si présent
        if (isset($data['nom'])) {
            $identite->setNom($data['nom']);
        }

        // Vérifie et assigne le sexe si présent
        if (isset($data['sexe'])) {
            $identite->setSexe($data['sexe']);
        }

        // Vérifie et assigne la date de naissance si présente
        if (isset($data['naissanceAt'])) {
            $identite->setNaissanceAt(
                new \DateTimeImmutable(
                    $data['naissanceAt']
                )
            );
        }

        return $identite;
    }

    /**
     * @return Identite
     */
    private function getEntity(): Identite
    {
        $identite = $this->getUser()->getIdentite();
        if (!$identite) {
            $identite = new Identite();
        }
        return $identite;
    }
}
