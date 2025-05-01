<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Ville;
use App\Entity\Adresse;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdresseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crée une instance de Pays
        $pays = new Pays();
        $pays->setLabel('France');
        $pays->setIndicatif(33);

        // Persiste le pays en base de données
        $manager->persist($pays);

        // Crée une instance de Ville
        $ville = new Ville();
        $ville->setLabel('Paris');
        $ville->setCodePostal(75000);
        $ville->setPays($pays); // Associe la ville au pays

        // Persiste la ville en base de données
        $manager->persist($ville);

        // Crée une instance d'Adresse
        $adresse = new Adresse();
        $adresse->setRue('123 Rue de Paris');
        $adresse->setAppartement(5);
        $adresse->setVilles($ville); // Associe l'adresse à la ville

        // Persiste l'adresse en base de données
        $manager->persist($adresse);


        // Enregistre toutes les entités en base de données
        $manager->flush();
    }
}