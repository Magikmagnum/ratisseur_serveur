<?php

namespace App\Services\Competence;

use App\Entity\Competences;
use App\Helpers\EntityHelper;
use App\Helpers\ImageUploadHelper;
use App\Helpers\HttpResponseHelper;
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

    public function creer(Request $request): array
    {
        $competence = $this->hydrateEntity(new Competences(), $request);

        if ($validationErrors = $this->entityHelper->validate($competence)) {
            throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityHelper->save($competence, true);
        return HttpResponseHelper::response(Response::HTTP_CREATED, $competence);
    }

    public function modifier($id, Request $request): array
    {
        $competence = $this->hydrateEntity($this->competencesRepository->find($id), $request);

        // Ici, nous vérifions si l'utilisateur actuel est autorisé à supprimer cette ressource
        if (!$this->isGranted('EDIT', $competence)) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        if ($validationErrors = $this->entityHelper->validate($competence)) {
            throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityHelper->save($competence);
        return HttpResponseHelper::response(Response::HTTP_OK, $competence);
    }

    public function supprimer($id): array
    {
        $competence = $this->competencesRepository->find($id);

        if (!$this->isGranted('DELETE', $competence)) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        $this->ImageUploadHelper->delete($competence->getEnseigne(), self::CUSTOME_IMAGE_DIRECTORY);
        $this->entityHelper->delete($competence);

        return HttpResponseHelper::response(Response::HTTP_OK);
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

    public function detail(int $id): array
    {
        if (!$this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return  HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->competencesRepository->findOneBy(["id" => $id])
        );
    }


    private function hydrateEntity(Competences $competence, Request $request): Competences
    {
        $data = $request->request->all();
        $data['enseigne'] = $request->files->get('enseigne');

        // Assurez-vous que l'utilisateur est défini
        if (!$competence->getUser()) {
            $competence->setUser($this->getUser());
        }

        // Vérifie et assigne le label si présent
        if (isset($data['label']) && !empty($data['label'])) {
            $competence->setLabel($this->competencesListeServices->getEntity($data['label']));
        }

        // Vérifie et assigne l'enseigne si présente
        if ($data['enseigne']) {
            $competence->setEnseigne(
                $this->ImageUploadHelper->upload($data['enseigne'], self::CUSTOME_IMAGE_DIRECTORY, self::CUSTOME_IMAGE_NAME, $competence->getEnseigne() ?: null)
            );
        }

        // Vérifie et assigne la description si présente
        if (isset($data['description']) && !empty($data['description'])) {
            $competence->setDescription($data['description']);
        }
        return $competence;
    }
}
