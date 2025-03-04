<?php

namespace App\Services\Competence;

use App\Helpers\EntityHelper;
use App\Entity\CompetencesListe;
use App\Exception\HydrationException;
use App\Exception\ValidationException;
use App\Repository\CompetencesListeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CompetencesListeServices extends AbstractController
{
    private CompetencesListeRepository $competencesListeRepository;
    protected EntityHelper $entityHelper;

    public function __construct(CompetencesListeRepository $competencesListeRepository, EntityHelper $entityHelper)
    {
        $this->entityHelper = $entityHelper;
        $this->competencesListeRepository = $competencesListeRepository;
    }


    public function getEntity(string $label): CompetencesListe
    {
        if (!$competenceListe = $this->competencesListeRepository->findOneBy(['label' => $label])) {
            $competenceListe = new CompetencesListe();
            $competenceListe->setLabel($label);

            if ($validationErrors = $this->entityHelper->validate($competenceListe)) {
                throw new ValidationException($validationErrors, Response::HTTP_BAD_REQUEST);
            }

            $this->entityHelper->save($competenceListe, true);
        }

        // Si aucune competenceListe n'est trouvée, lancer une exception avec un message plus parlant
        if ($competenceListe === null) {
            throw new HydrationException('Nom de competence non trouvée pour l\'ID fourni.');
        }

        return $competenceListe;
    }
}
