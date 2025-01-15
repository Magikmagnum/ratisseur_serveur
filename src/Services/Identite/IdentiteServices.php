<?php

namespace App\Services\Identite;

use App\Entity\Identite;
use App\Helpers\EntityHelper;
use App\Traits\EntityCrudTrait;
use App\Traits\EntityHydratorTrait;
use App\Services\Identite\IdentiteInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IdentiteServices extends AbstractController implements IdentiteInterface
{
    use EntityHydratorTrait;
    use EntityCrudTrait;

    protected EntityHelper $entityHelper;

    /**
     * @param EntityHelper $entityHelper
     */
    public function __construct(EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
    }

    /**
     * @param \App\Entity\Identite $identite
     * @param array $data
     * @return \App\Entity\Identite
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
        return $identite;
    }

    /**
     * @return Identite
     */
    private function newEntity(): Identite
    {
        return new Identite();
    }
}
