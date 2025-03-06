<?php

namespace App\Services\Identite;

use App\Entity\Identite;
use App\Helpers\EntityHelper;
use App\Helpers\ImageUploadHelper;
use App\Traits\HttpRequestHydrator;
use App\Exception\HydrationException;
use App\Traits\EntityCrudSingleTrait;
use App\Services\Interfaces\ServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

enum MessageError: string
{
    case NO_FILE = "No file uploaded";
    case UPLOAD_FAILED = "File upload failed";
}

/**
 * @implements ServiceInterface<Identite>
 */
class IdentiteServices extends AbstractController implements ServiceInterface
{
    use HttpRequestHydrator;
    use EntityCrudSingleTrait;

    const  CUSTOME_IMAGE_DIRECTORY = "images/identites";
    const  CUSTOME_IMAGE_NAME = "identite_";

    private ImageUploadHelper $ImageUploadHelper;
    protected EntityHelper $entityHelper;

    public function __construct(EntityHelper $entityHelper, ImageUploadHelper $ImageUploadHelper)
    {
        $this->entityHelper = $entityHelper;
        $this->ImageUploadHelper = $ImageUploadHelper;
    }

    /**
     * @param Identite $identite
     * @param array $data
     * @return Identite
     */
    public function mapDataToEntity(Object $identite, array $data): Identite
    {
        // Assurez-vous que l'utilisateur est défini
        if (!$identite->getUser()) {
            $identite->setUser($this->getUser());
        }

        // Vérifie et assigne le nom si présent
        if (isset($data['nom'])) {
            $identite->setNom($data['nom']);
        }

        // Vérifie et assigne le sexe si présent
        if (isset($data['sexe'])) {
            $identite->setSexe($data['sexe']);
        }

        // Vérifie et assigne la date de naissance si présente
        if (isset($data['naissanceAt'])) {
            $identite->setNaissanceAt(
                new \DateTimeImmutable(
                    $data['naissanceAt']
                )
            );
        }

        // Vérifie et assigne l'avatar si présente
        if (isset($data['files']['avatar'])) {
            $identite->setAvatar(
                $this->ImageUploadHelper->upload($data['files']['avatar'], self::CUSTOME_IMAGE_DIRECTORY, self::CUSTOME_IMAGE_NAME, $identite->getAvatar() ?: null)
            );
        }

        return $identite;
    }

    /**
     * @return Identite
     */
    public function getEntity(): Identite
    {
        $identite = $this->getUser()->getIdentite();
        if (!$identite) {
            $identite = new Identite();
        }

        // Si aucune identite n'est trouvée, lancer une exception avec un message plus parlant
        if ($identite === null) {
            throw new HydrationException('Identite non trouvée pour l\'ID fourni.');
        }

        return $identite;
    }



    /**
     * @return Identite
     */
    private function beforDeleteEntity(Identite $identite): void
    {
        $this->ImageUploadHelper->delete($identite->getAvatar(), self::CUSTOME_IMAGE_DIRECTORY);
    }
}
