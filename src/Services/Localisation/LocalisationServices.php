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

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->localisationRepository->findBy(['user' => $user])
        );
    }

    public function liste(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->localisationRepository->findAllExcepteUser($user)
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

        // Vérifie et assigne la rue si présente
        if (isset($data['timestamp'])) {
            $localisation->setTimestamp($data['timestamp']);
        }

        // Vérifie et assigne la coord et le le code postal si présente
        if (isset($data['coord'])) {
            $coord = $this->coordServices->getEntity($data['coord']);
            if ($coord) {
                $localisation->setCoord($coord);
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
}
