<?php

namespace App\Services\Localisation;

use App\Helpers\EntityHelper;
use App\Entity\Coords;
use App\Exception\ValidationException;
use App\Repository\CoordsRepository;
use App\Services\Adresse\PaysServices;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CoordServices extends AbstractController
{
    private CoordsRepository $coordRepository;
    private PaysServices $paysServices;
    protected EntityHelper $entityHelper;

    public function __construct(CoordsRepository $coordRepository, PaysServices $paysServices, EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
        $this->coordRepository = $coordRepository;
    }

    /**
     * Récupère ou crée une entité Coord en fonction des critères fournis.
     *
     * @param array $criteria Tableau contenant les champs `label` et `codePostal`.
     * @throws \App\Exception\ValidationException Si les critères sont invalides.
     * @return \App\Entity\Coords L'entité Coord correspondante.
     */
    public function getEntity(array $criteria): Coords
    {
        // Validation des critères requis
        if (empty($criteria['latitude']) || empty($criteria['longitude'])) {
            throw new ValidationException(
                ['message' => 'Les champs "latitude", "longitude" sont obligatoires pour créer ou récupérer une Coord.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Recherche de la coord existante
        $coord = $this->coordRepository->findOneBy(
            [
                'latitude' => $criteria['latitude'],
                'longitude' => $criteria['longitude'],
                'altitude' => $criteria['altitude'],
                'accuracy' => $criteria['accuracy'],
                'altitudeAccuracy' => $criteria['altitudeAccuracy'],
                'heading' => $criteria['heading'],
                'speed' => $criteria['speed'],
            ]
        );

        // Création de la coord si elle n'existe pas
        if (!$coord) {
            $coord = new Coords();

            // Initialisation des valeurs de position avec des valeurs par défaut
            $coord->setLatitude($criteria['latitude'] ?? 0.0);
            $coord->setLongitude($criteria['longitude'] ?? 0.0);
            $coord->setAltitude($criteria['altitude'] ?? null);
            $coord->setAccuracy($criteria['accuracy'] ?? null);
            $coord->setAltitudeAccuracy($criteria['altitudeAccuracy'] ?? null);
            $coord->setHeading($criteria['heading'] ?? null);
            $coord->setSpeed($criteria['speed'] ?? null);

            // Validation de l'entité Coords
            $validationErrors = $this->entityHelper->validate($coord);
            if ($validationErrors) {
                throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
            }

            // Sauvegarde de la nouvelle coord
            $this->entityHelper->save($coord, true);
        }

        return $coord;
    }
}
