<?php 

namespace App\Tests\Repository;

use App\Repository\AdresseRepository;
use App\DataFixtures\AdresseFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

/**
 * Classe de test pour AdresseRepository.
 */
class AdresseRepositoryTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private AdresseRepository $adresseRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        // Chargement des fixtures
        $this->databaseTool = $container->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AdresseFixtures::class,
        ]);

        // Récupération du repository AdresseRepository
        $this->adresseRepository = $container->get(AdresseRepository::class);
    }

    /**
     * Teste si le nombre d'adresses enregistrées est bien égal au nombre attendu.
     */
    public function testCount(): void
    {
        // Compte les adresses en base de données
        $addressesCount = $this->adresseRepository->count([]);

        // Vérifie si le nombre d'adresses est bien celui attendu
        $this->assertSame(1, $addressesCount, "Le nombre d'adresses en base doit être de 1.");
    }
}