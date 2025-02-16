<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user$i@ratisseur.com");

            // Hachage sécurisé du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, "couCou1234");
            $user->setPassword($hashedPassword);

            // Ajout d'un rôle par défaut
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}