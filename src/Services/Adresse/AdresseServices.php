<?php

namespace App\Services\Adresse;

use App\Entity\Adresse;
use App\Helpers\EntityHelper;
use App\Traits\EntityHydratorTrait;
use App\Repository\AdresseRepository;
use App\Traits\EntityCrudSingleTrait;
use App\Controller\AbstractController;
use App\Services\Adresse\VilleServices;
use App\Services\Interfaces\ServiceInterface;

/**
 * @implements ServiceInterface<Adresse>
 */
class AdresseServices extends AbstractController implements ServiceInterface
{
    use EntityHydratorTrait;
    use EntityCrudSingleTrait;

    private AdresseRepository $adresseRepository;
    private VilleServices $villeServices;
    protected EntityHelper $entityHelper;

    public function __construct(AdresseRepository $adresseRepository, VilleServices $villeServices, EntityHelper $entityHelper)
    {
        $this->adresseRepository = $adresseRepository;
        $this->villeServices = $villeServices;
        $this->entityHelper = $entityHelper;
    }

    /**
     * Mappe les données fournies dans une entité Adresse.
     *
     * @param Adresse $adresse L'entité Adresse à hydrater.
     * @param array $data Les données à mapper dans l'entité.
     * @return Adresse L'entité Adresse hydratée.
     */
    public function mapDataToEntity(Object $adresse, array $data): Adresse
    {
        // Assurez-vous que l'utilisateur est défini
        if (!$adresse->getId()) {
            $adresse->addUser($this->getUser());
        }

        // Vérifie et assigne la rue si présente
        if (isset($data['rue'])) {
            $adresse->setRue($data['rue']);
        }

        // Vérifie et assigne l'appartement si présent
        if (isset($data['appartement'])) {
            $adresse->setAppartement((int) $data['appartement']);
        }

        // Vérifie et assigne la ville et le le code postal si présente
        if (isset($data['ville']) && isset($data['codePostal'])) {
            $ville = $this->villeServices->getEntity($data);
            if ($ville) {
                $adresse->setVilles($ville);
            }
        }

        return $adresse;
    }

    /**
     * @return Adresse
     */
    public function getEntity(): Adresse
    {
        $adresse = $this->getUser()->getadresse();
        if (!$adresse) {
            $adresse = new Adresse();
        }
        return $adresse;
    }
}
