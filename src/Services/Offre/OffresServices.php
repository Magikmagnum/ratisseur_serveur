<?php

namespace App\Services\Offre;

use App\Entity\Offres;
use App\Helpers\EntityHelper;
use App\Helpers\HttpResponseHelper;
use App\Traits\EntityCrudListTrait;
use App\Traits\EntityHydratorTrait;
use App\Repository\OffresRepository;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetenceInterface;
use App\Services\Competence\CompetencesServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OffresServices extends AbstractController implements CompetenceInterface
{
    use EntityHydratorTrait;
    use EntityCrudListTrait;

    private OffresRepository $offresRepository;
    private CompetencesServices $competencesServices;
    protected EntityHelper $entityHelper;



    public function __construct(OffresRepository $offresRepository, EntityHelper $entityHelper, CompetencesServices $competencesServices)
    {
        $this->offresRepository = $offresRepository;
        $this->entityHelper = $entityHelper;
        $this->competencesServices = $competencesServices;
    }

    public function listeUtilisateur(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->offresRepository->findBy(['user' => $user])
        );
    }

    public function liste(): array
    {
        if (!$user = $this->getUser()) {
            throw new ValidationException([], Response::HTTP_FORBIDDEN);
        }

        return HttpResponseHelper::response(
            Response::HTTP_OK,
            $this->offresRepository->findAllExcepteUser($user)
        );
    }

    /**
     * 
     * @param Offres $offre
     * @param array $data
     * @return Offres
     */
    public function mapDataToEntity(Offres $offre, array $data): Offres
    {

        if (!$offre->getUser()) {
            $offre->setUser($this->getUser());
        }

        if (isset($data['competence'])) {
            $competence = $this->competencesServices->getEntity($data['competence']);
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
    private function getEntity(?int $id = null): Offres
    {
        if ($id === null) {
            return new Offres();
        }

        $offre = $this->offresRepository->findOneBy(["id" => $id]);
        return $offre;
    }
}
