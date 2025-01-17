<?php

namespace App\Services\Competence;

use App\Entity\Competences;
use App\Helpers\EntityHelper;
use App\Traits\EntityCrudListTrait;
use App\Helpers\ImageUploadHelper;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityHydratorTrait;
use App\Exception\ValidationException;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetenceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

enum MessageError: string
{
    case NO_FILE = "No file uploaded";
    case UPLOAD_FAILED = "File upload failed";
}

class CompetencesServices extends AbstractController implements CompetenceInterface
{
    use EntityHydratorTrait;
    use EntityCrudListTrait;

    const  CUSTOME_IMAGE_DIRECTORY = "images/competences";
    const  CUSTOME_IMAGE_NAME = "competences_";

    private ImageUploadHelper $ImageUploadHelper;
    private CompetencesRepository $competencesRepository;
    private CompetencesListeServices $competencesListeServices;
    protected EntityHelper $entityHelper;



    public function __construct(CompetencesRepository $competencesRepository, CompetencesListeServices $competencesListeServices, ImageUploadHelper $ImageUploadHelper, EntityHelper $entityHelper)
    {
        $this->competencesRepository = $competencesRepository;
        $this->ImageUploadHelper = $ImageUploadHelper;
        $this->competencesListeServices = $competencesListeServices;
        $this->entityHelper = $entityHelper;
    }

    public function listeUtilisateur(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->competencesRepository->findBy(['user' => $user])
        );
    }

    public function liste(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->competencesRepository->findAllExcepteUser($user)
        );
    }

    /**
     * @param Competences $competence
     * @param array $data
     * @return Competences
     */
    private function mapDataToEntity(Competences $competence, array $data): Competences
    {
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
    private function getEntity(?int $id = null): Competences
    {
        if ($id === null) {
            return new Competences();
        }

        $competence = $this->competencesRepository->findOneBy(["id" => $id]);
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
