<?php

namespace App\Services\Offre;

use App\Entity\Offres;
use App\Helpers\EntityHelper;
use App\Traits\EntityFilterTrait;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\EntityHydratorTrait;
use App\Repository\OffresRepository;
use App\Exception\HydrationException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetencesServices;
use App\Services\Interfaces\ServiceListInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OffresServices extends AbstractController implements ServiceListInterface
{
    use EntityHydratorTrait;
    use EntityCrudListTrait;
    use EntityFilterTrait;

    private OffresRepository $offresRepository;
    private CompetencesServices $competencesServices;
    protected EntityHelper $entityHelper;

    public function __construct(OffresRepository $offresRepository, EntityHelper $entityHelper, CompetencesServices $competencesServices)
    {
        $this->offresRepository = $offresRepository;
        $this->entityHelper = $entityHelper;
        $this->competencesServices = $competencesServices;
    }

    /**
     * 
     * @param Offres $offre
     * @param array $data
     * @return Offres
     */
    public function mapDataToEntity(Object $offre, array $data): Offres
    {

        if (!$offre->getUser()) {
            $offre->setUser($this->getUser());
        }

        if (isset($data['competence_id'])) {
            $competence = $this->competencesServices->getEntity($data['competence_id']);
            $competence && $offre->setCompetence($competence);
        }

        if (isset($data['libelle'])) {
            $offre->setLibelle($data['libelle']);
        }

        return $offre;
    }

    /**
     * @param ?int $id L'identifiant de la compétence à retourner. Si null, retourne une nouvelle.
     * @return Offres
     */
    public function getEntity(?int $id = null): Offres
    {
        if ($id === null) {
            return new Offres();
        }

        $offre = $this->offresRepository->findOneBy(["id" => $id]);

        
        // Si aucune localisation n'est trouvée, lancer une exception avec un message plus parlant
        if ($offre === null) {
            throw new HydrationException('Offre non trouvée pour l\'ID fourni.');
        }

        return $offre;
    }
}
