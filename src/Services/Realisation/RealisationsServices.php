<?php

namespace App\Services\Realisation;

use App\Entity\Realisations;
use App\Helpers\EntityHelper;
use App\Traits\EntityFilterTrait;
use App\Helpers\ImageUploadHelper;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\EntityHydratorTrait;
use App\Exception\HydrationException;
use App\Exception\ValidationException;
use App\Repository\RealisationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetencesServices;
use App\Services\Interfaces\ServiceListInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

enum MessageError: string
{
    case NO_FILE = "No file uploaded";
    case UPLOAD_FAILED = "File upload failed";
}

/**
 * @implements ServiceListInterface<Realisations>
 */
class RealisationsServices extends AbstractController implements ServiceListInterface
{
    use EntityHydratorTrait;
    use EntityCrudListTrait;
    use EntityFilterTrait;

    const  CUSTOME_IMAGE_DIRECTORY = "images/realisations";
    const  CUSTOME_IMAGE_NAME = "realisations_";
    private ImageUploadHelper $ImageUploadHelper;
    private RealisationsRepository $repository;
    protected EntityHelper $entityHelper;
    private CompetencesServices $competencesServices;

    public function __construct(RealisationsRepository $repository, ImageUploadHelper $ImageUploadHelper, EntityHelper $entityHelper, CompetencesServices $competencesServices)
    {
        $this->repository = $repository;
        $this->ImageUploadHelper = $ImageUploadHelper;
        $this->entityHelper = $entityHelper;
        $this->competencesServices = $competencesServices;
    }

    /**
     * @param Realisations $realisation L'entité de type Realisations à hydrater
     * @param array $data Le tableau des données à mapper
     * @return Realisations L'entité Realisations hydratée
     */
    public function mapDataToEntity(object $realisation, array $data): Realisations
    {
        // Assurez-vous que la competence est definit
        if (!$realisation->getCompetence()) {
            $competence = $this->competencesServices->getEntity($data['attributes']['id']);
            $realisation->setCompetence($competence);
        }

        // Vérifie et assigne le label si présent
        if (isset($data['label']) && !empty($data['label'])) {
            $realisation->setLabel($data['label']);
        }

        // Vérifie et assigne la description si présente
        if (isset($data['description']) && !empty($data['description'])) {
            $realisation->setDescription($data['description']);
        }
        return $realisation;
    }

    /**
     * @param ?int $id L'identifiant de la compétence à retourner. Si null, retourne une nouvelle.
     * @return Realisations
     */
    public function getEntity(?int $id = null): Realisations
    {
        if ($id === null) {
            return new Realisations();
        }

        $realisation = $this->repository->findOneBy(["id" => $id]);

        // Si aucune compétence n'est trouvée, lancer une exception avec un message plus parlant
        if ($realisation === null) {
            throw new HydrationException('Réalisation non trouvée pour l\'ID fourni.');
        }

        return $realisation;
    }
}