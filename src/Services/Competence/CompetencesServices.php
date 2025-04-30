<?php

namespace App\Services\Competence;

use App\Entity\Competences;
use App\Helpers\EntityHelper;
use App\Helpers\ImageUploadHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\HttpRequestHydrator;
use App\Exception\HydrationException;
use App\Controller\AbstractController;
use App\Repository\CompetencesRepository;
use App\Services\ServiceListInterfaces;

/**
 * @implements ServiceListInterfaces<Competences>
 */
class CompetencesServices extends AbstractController implements ServiceListInterfaces
{
    use HttpRequestHydrator;
    use EntityCrudListTrait;

    const  CUSTOME_IMAGE_DIRECTORY = "images/competences";
    const  CUSTOME_IMAGE_NAME = "competences_";
    private ImageUploadHelper $ImageUploadHelper;
    private CompetencesRepository $repository;
    private CompetencesListeServices $competencesListeServices;
    protected EntityHelper $entityHelper;

    public function __construct(CompetencesRepository $repository, CompetencesListeServices $competencesListeServices, ImageUploadHelper $ImageUploadHelper, EntityHelper $entityHelper)
    {
        $this->repository = $repository;
        $this->ImageUploadHelper = $ImageUploadHelper;
        $this->competencesListeServices = $competencesListeServices;
        $this->entityHelper = $entityHelper;
    }

    /**
     * @param Competences $competence L'entité de type Competences à hydrater
     * @param array $data Le tableau des données à mapper
     * @return Competences L'entité Competences hydratée
     */
    public function mapDataToEntity(object $competence, array $data): Competences
    {
        if (!$competence instanceof Competences) {
            throw new \InvalidArgumentException("L'objet doit être une instance de Competences.");
        }

        // Assurez-vous que l'utilisateur est défini
        if (!$competence->getUser()) {
            $competence->setUser($this->getUser());
        }

        // Vérifie et assigne le label si présent
        if (isset($data['label']) && !empty($data['label'])) {
            $competence->setLabel($this->competencesListeServices->getEntity($data['label']));
        }

        // Vérifie et assigne l'enseigne si présente
        if (isset($data['files']['enseigne'])) {
            $competence->setEnseigne(
                $this->ImageUploadHelper->upload($data['files']['enseigne'], self::CUSTOME_IMAGE_DIRECTORY, self::CUSTOME_IMAGE_NAME, $competence->getEnseigne() ?: null)
            );
        }

        // Vérifie et assigne la description si présente
        if (isset($data['description']) && !empty($data['description'])) {
            $competence->setDescription($data['description']);
        }
        return $competence;
    }


    /**
     * @param ?int $id L'identifiant de la compétence à retourner. Si null, retourne une nouvelle.
     * @return Competences
     */
    public function getEntity(?int $id = null): Competences
    {
        if ($id === null) {
            return new Competences();
        }

        $competence = $this->repository->findOneBy(["id" => $id]);

        // Si aucune compétence n'est trouvée, lancer une exception avec un message plus parlant
        if ($competence === null) {
            throw new HydrationException('Compétence non trouvée pour l\'ID fourni.');
        }

        return $competence;
    }

    /**
     * @return Competences
     */
    private function beforDeleteEntity(Competences $competence): void
    {
        $this->ImageUploadHelper->delete($competence->getEnseigne(), self::CUSTOME_IMAGE_DIRECTORY);
    }
}
