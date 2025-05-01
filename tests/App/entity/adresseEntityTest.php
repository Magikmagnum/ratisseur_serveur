<?php

namespace App\Tests\Entity;

use App\Entity\Ville;
use App\Entity\Adresse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdresseEntityTest extends KernelTestCase
{
    private $validator;

    private function getValidator(): ValidatorInterface
    {
        self::bootKernel();
        return self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidationSuccess()
    {
        $adresse = new Adresse();
        $adresse->setRue('123 Rue de Paris');
        $adresse->setAppartement(5);
        $adresse->setVilles(new Ville());

        $errors = $this->getValidator()->validate($adresse);

        // Aucune erreur ne doit être retournée
        $this->assertCount(0, $errors);
    }

    public function testRueIsBlank()
    {
        $adresse = new Adresse();
        $adresse->setRue(''); // Rue vide
        $adresse->setAppartement(5);
        $adresse->setVilles(new Ville());

        $errors = $this->getValidator()->validate($adresse);

        // Une erreur doit être retournée pour la rue vide
        $this->assertCount(1, $errors);
        $this->assertEquals('La rue ne peut pas être vide.', $errors[0]->getMessage());
    }

    public function testRueIsTooLong()
    {
        $adresse = new Adresse();
        $adresse->setRue(str_repeat('a', 256)); // Rue de 256 caractères
        $adresse->setAppartement(5);
        $adresse->setVilles(new Ville());

        $errors = $this->getValidator()->validate($adresse);

        // Une erreur doit être retournée pour la rue trop longue
        $this->assertCount(1, $errors);
        $this->assertEquals('La rue ne peut pas dépasser 255 caractères.', $errors[0]->getMessage());
    }

    public function testAppartementIsNegative()
    {
        $adresse = new Adresse();
        $adresse->setRue('123 Rue de Paris');
        $adresse->setAppartement(-1); // Appartement négatif
        $adresse->setVilles(new Ville());

        $errors = $this->getValidator()->validate($adresse);

        // Une erreur doit être retournée pour l'appartement négatif
        $this->assertCount(1, $errors);
        $this->assertEquals('Le numéro d\'appartement doit être positif ou zéro.', $errors[0]->getMessage());
    }

    public function testVillesIsNull()
    {
        $adresse = new Adresse();
        $adresse->setRue('123 Rue de Paris');
        $adresse->setAppartement(5);
        $adresse->setVilles(null); // Ville non renseignée

        $errors = $this->getValidator()->validate($adresse);

        // Une erreur doit être retournée pour la ville non renseignée
        $this->assertCount(1, $errors);
        $this->assertEquals('La ville doit être renseignée.', $errors[0]->getMessage());
    }

    public function testVirtualProperties()
    {
        $ville = new Ville();
        $ville->setLabel('Paris');
        $ville->setCodePostal(75000);

        $adresse = new Adresse();
        $adresse->setRue('123 Rue de Paris');
        $adresse->setAppartement(5);
        $adresse->setVilles($ville);

        // Test des propriétés virtuelles
        $this->assertEquals('Paris', $adresse->getVille());
        $this->assertEquals(75000, $adresse->getCodePostal());
    }
}