<?php 

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de test pour le UserRepository.
 */
class UserRepositoryTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        // Chargement des fixtures
        $this->databaseTool = $container->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            UserFixtures::class,
        ]);

        // Récupération du UserRepository
        $this->userRepository = $container->get(UserRepository::class);
    }

    /**
     * Teste si le nombre d'utilisateurs enregistrés est bien égal à 10.
     */
    public function testCount(): void
    {
        // Compte les utilisateurs en base de données
        $usersCount = $this->userRepository->count([]);

        // Vérifie si le nombre d'utilisateurs est bien égal à 10
        $this->assertSame(10, $usersCount, "Le nombre d'utilisateurs en base doit être de 10.");
    }
}