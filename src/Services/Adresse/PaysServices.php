<?php

namespace App\Services\Adresse;

use App\Helpers\EntityHelper;
use App\Entity\Pays;
use App\Exception\ValidationException;
use App\Repository\PaysRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PaysServices extends AbstractController
{
    private PaysRepository $paysRepository;
    protected EntityHelper $entityHelper;

    public function __construct(PaysRepository $paysRepository, EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
        $this->paysRepository = $paysRepository;
    }

    /**
     * Récupère ou crée une entité Pays en fonction des critères fournis.
     *
     * @param array $criteria Tableau contenant les champs `label` et `codePostal`.
     * @throws \App\Exception\ValidationException Si les critères sont invalides.
     * @return \App\Entity\Pays L'entité Pays correspondante.
     */
    public function getEntity(string $label): Pays
    {
        if (!$pays = $this->paysRepository->findOneBy(['label' => $label])) {
            $pays = new Pays();
            $pays->setLabel($label);

            if ($validationErrors = $this->entityHelper->validate($pays)) {
                throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
            }

            $this->entityHelper->save($pays, true);
        }
        return $pays;
    }
}
