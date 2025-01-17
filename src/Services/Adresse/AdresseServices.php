<?php

namespace App\Services\Adresse;

use App\Entity\Pays;
use App\Entity\Ville;
use App\DTO\AdresseDTO;
use App\Entity\Adresse;
use App\Helpers\EntityHelper;
use App\Services\DTOServices;
use App\Traits\EntityCrudSingleTrait;
use App\Repository\PaysRepository;
use App\Helpers\HttpResponseHelper;
use App\Repository\VilleRepository;
use App\Traits\EntityHydratorTrait;
use App\Repository\AdresseRepository;
use App\Controller\AbstractController;
use App\Exception\ValidationException;
use App\Services\Adresse\VilleServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Adresse\AdresseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdresseServices extends AbstractController implements AdresseInterface
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
     * @param \App\Entity\Adresse $adresse L'entité Adresse à hydrater.
     * @param array $data Les données à mapper dans l'entité.
     * @return \App\Entity\Adresse L'entité Adresse hydratée.
     */
    private function mapDataToEntity(Adresse $adresse, array $data): Adresse
    {
        $user = $this->getUser();
        $adresseUser = $adresse->getUser();
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
    public function getEntity(): Adresse|null
    {
        $adresse = $this->getUser()->getAdresse();
        return $adresse;
    }

    /**
     * @return Adresse
     */
    private function newEntity(): Adresse
    {
        return new Adresse();
    }
}
