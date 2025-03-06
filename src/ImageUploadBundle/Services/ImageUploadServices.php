<?php

namespace ImageUploadBundle\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

enum MessageErreur: string
{
    case REPERTOIRE_INEXISTANT = 'Le répertoire "%s" est inexistant.';
    case FICHIER_INTROUVABLE = 'Le fichier "%s" est introuvable dans le répertoire.';
    case CREATION_REPERTOIRE_IMPOSSIBLE = 'Impossible de créer le répertoire "%s".';
    case SUPPRESSION_FICHIER_IMPOSSIBLE = 'Impossible de supprimer le fichier "%s".';
    case TELECHARGEMENT_ECHOUE = 'Le téléchargement du fichier a échoué : %s';
}

class ImageUploadServices
{
    public function upload(UploadedFile $uploadedFile, string $directory, ?string $customName = null, ?string $oldImageName = null): string
    {
        if ($oldImageName) {
            $this->delete($oldImageName, $directory);
        }
        return $this->save($uploadedFile, $directory, $customName);
    }

    public function save(UploadedFile $uploadedFile, string $directory, ?string $customName = null): string
    {
        $originalFilename = $customName ?? pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf(MessageErreur::CREATION_REPERTOIRE_IMPOSSIBLE->value, $directory));
            }
            $uploadedFile->move($directory, $newFilename);
            return $newFilename;
        } catch (FileException $e) {
            throw new \RuntimeException(sprintf(MessageErreur::TELECHARGEMENT_ECHOUE->value, $e->getMessage()));
        }
    }

    public function delete(string $imageName, string $directory): bool
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException(sprintf(MessageErreur::REPERTOIRE_INEXISTANT->value, $directory));
        }

        $filePath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $imageName;

        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf(MessageErreur::FICHIER_INTROUVABLE->value, $imageName));
        }

        if (!unlink($filePath)) {
            throw new \RuntimeException(sprintf(MessageErreur::SUPPRESSION_FICHIER_IMPOSSIBLE->value, $filePath));
        }

        return true;
    }
}