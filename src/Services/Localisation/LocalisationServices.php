<?php

namespace App\Services\Localisation;

use App\Entity\Localisation;
use App\Helpers\EntityHelper;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\HttpRequestHydrator;
use App\Exception\HydrationException;
use App\Controller\AbstractController;
use App\Exception\ValidationException;
use App\Repository\LocalisationRepository;
use App\Services\Localisation\CoordServices;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ServiceListInterfaces;
use App\Traits\FilterType;

/**
 * @implements ServiceListInterfaces<Localisation>
 */
class LocalisationServices extends AbstractController implements ServiceListInterfaces
{
    use HttpRequestHydrator;
    use EntityCrudListTrait;

    private LocalisationRepository $repository;
    private CoordServices $coordServices;
    protected EntityHelper $entityHelper;

    public function __construct(LocalisationRepository $repository, CoordServices $coordServices, EntityHelper $entityHelper)
    {
        $this->repository = $repository;
        $this->coordServices = $coordServices;
        $this->entityHelper = $entityHelper;
    }

     /**
     * Récupère les données en fonction du type spécifié.
     *
     * @param FilterType $type Le type de filtre a appliquer (`BY_USER`, `BY_OTHER`, `BY_ALL`).
     * @param ?int $id l'id de l'entity à filtrer
     * @param array $criteria Tableau de critères supplémentaires à appliquer au filtre.
     * @return array Un tableau contenant la liste des entités filtrées.
     * 
     * @throws ValidationException Si l'utilisateur n'est pas authentifié.
     */
    public function getData(FilterType $type = FilterType::BY_ALL, ?int $id = null, ?array $criteria = []): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->normalizer(
                $this->fetchData($type, $user, $id)
            )

        );

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            
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
    public function getEntity(?int $id = null): Localisation
    {
        if ($id === null) {
            return new Localisation();
        }

        $localisation = $this->repository->findOneBy(["id" => $id]);

        // Si aucune localisation n'est trouvée, lancer une exception avec un message plus parlant
        if ($localisation === null) {
            throw new HydrationException('Localisation non trouvée pour l\'ID fourni.');
        }


        return $localisation;
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
            $formattedLocations[] = ['id' => $location->getId(), 'location' => $location];
        }
        return $formattedLocations;
    }
}
