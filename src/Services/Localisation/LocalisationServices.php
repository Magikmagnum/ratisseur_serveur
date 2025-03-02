<?php

namespace App\Services\Localisation;

use App\Entity\Localisation;
use App\Helpers\EntityHelper;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\EntityHydratorTrait;
use App\Controller\AbstractController;
use App\Exception\ValidationException;
use App\Repository\LocalisationRepository;
use App\Services\Localisation\CoordServices;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Interfaces\ServiceListInterface;

/**
 * @implements ServiceListInterface<Localisation>
 */
class LocalisationServices extends AbstractController implements ServiceListInterface
{
    use EntityHydratorTrait;
    use EntityCrudListTrait;

    private LocalisationRepository $localisationRepository;
    private CoordServices $coordServices;
    protected EntityHelper $entityHelper;

    public function __construct(LocalisationRepository $localisationRepository, CoordServices $coordServices, EntityHelper $entityHelper)
    {
        $this->localisationRepository = $localisationRepository;
        $this->coordServices = $coordServices;
        $this->entityHelper = $entityHelper;
    }

    public function listeUtilisateur(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        $locatisations  = $this->localisationRepository->findBy(['user' => $user]);

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->normalizer($locatisations)
        );
    }

    public function liste(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        $locatisations = $this->localisationRepository->findAllExcepteUser($user);

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->normalizer($locatisations)
            
        );
    }

    /**
     * Mappe les données fournies dans une entité Localisation.
     *
     * @param Localisation $localisation L'entité Localisation à hydrater.
     * @param array $data Les données à mapper dans l'entité.
     * @return Localisation L'entité Localisation hydratée.
     */
    public function mapDataToEntity(Object $localisation, array $data): Localisation
    {
        // Assurez-vous que l'utilisateur est défini
        if (!$localisation->getId()) {
            $localisation->setUser($this->getUser());
        }

        $location = $data['location'];


        // Vérifie et assigne la rue si présente
        if (!empty($location['timestamp'])) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($location['timestamp'] / 1000);
            $localisation->setTimestamp($dateTime);
        }


        // Vérifie et assigne la coord et le le code postal si présente
        if (isset($location['coords'])) {
            $coord = $this->coordServices->getEntity($location['coords']);
            if ($coord) {
                $localisation->setCoords($coord);
            }
        }

        return $localisation;
    }

    /**
     * @return Localisation
     */
    public function getEntity(int $id = null): Localisation
    {
        if ($id === null) {
            return new Localisation();
        }

        $competence = $this->localisationRepository->findOneBy(["id" => $id]);
        return $competence;
    }

    /**
     * Transforme les résultats du repository en un format adapté au client.
     *
     * @param array $locations Liste des emplacements récupérés depuis le repository.
     * @return array Tableau formaté contenant les emplacements sous la clé 'location'.
     */
    private function normalizer(array $locations): array
    {
        $formattedLocations = [];

        foreach ($locations as $location) {
            $formattedLocations[] = ['location' => $location];
        }

        return $formattedLocations;
    }
}
