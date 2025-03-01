<?php

namespace App\Services\Adresse;

use App\Helpers\EntityHelper;
use App\Entity\Ville;
use App\Exception\ValidationException;
use App\Repository\VilleRepository;
use App\Services\Adresse\PaysServices;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VilleServices extends AbstractController
{
    private VilleRepository $villeRepository;
    private PaysServices $paysServices;
    protected EntityHelper $entityHelper;

    public function __construct(VilleRepository $villeRepository, PaysServices $paysServices, EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
        $this->villeRepository = $villeRepository;
        $this->paysServices = $paysServices;
    }

    /**
     * Récupère ou crée une entité Ville en fonction des critères fournis.
     *
     * @param array $criteria Tableau contenant les champs `label` et `codePostal`.
     * @throws \App\Exception\ValidationException Si les critères sont invalides.
     * @return \App\Entity\Ville L'entité Ville correspondante.
     */
    public function getEntity(array $criteria): Ville
    {
        // Validation des critères requis
        if (empty($criteria['ville']) || empty($criteria['codePostal']) || empty($criteria['pays'])) {
            throw new ValidationException(
                ['message' => 'Les champs "ville", "codePostal" et "pays" sont obligatoires pour créer ou récupérer une Ville.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Recherche de la ville existante
        $ville = $this->villeRepository->findOneBy(
            [
                'label' => $criteria['ville'],
                'codePostal' => $criteria['codePostal']
            ]
        );

        // Création de la ville si elle n'existe pas
        if (!$ville) {
            $ville = new Ville();
            $ville->setLabel($criteria['ville']);
            $ville->setCodePostal($criteria['codePostal']);

            // Recherche et association du pays
            $pays = $this->paysServices->getEntity($criteria['pays']);
            if (!$pays) {
                throw new ValidationException(
                    ['message' => 'Le pays spécifié est introuvable.'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $ville->setPays($pays);

            // Validation de l'entité Ville
            $validationErrors = $this->entityHelper->validate($ville);
            if ($validationErrors) {
                throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
            }

            // Sauvegarde de la nouvelle ville
            $this->entityHelper->save($ville, true);
        }

        return $ville;
    }
}
