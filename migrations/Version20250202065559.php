<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202065559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, villes_id INT DEFAULT NULL, rue VARCHAR(255) NOT NULL, appartement INT DEFAULT NULL, INDEX IDX_C35F0816286C17BC (villes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, label_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', enseigne VARCHAR(255) DEFAULT NULL, INDEX IDX_DB2077CE33B92F39 (label_id), INDEX IDX_DB2077CEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_liste (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', valide TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprises (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, etablissement TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiences (id INT AUTO_INCREMENT NOT NULL, label_id INT NOT NULL, entreprise_id INT DEFAULT NULL, user_id INT NOT NULL, description LONGTEXT DEFAULT NULL, debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', en_cour TINYINT(1) DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_82020E7033B92F39 (label_id), INDEX IDX_82020E70A4AEAFEA (entreprise_id), INDEX IDX_82020E70A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiences_liste (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, valide TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations (id INT AUTO_INCREMENT NOT NULL, label_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, user_id INT NOT NULL, description LONGTEXT DEFAULT NULL, debut_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', en_cour TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4090213733B92F39 (label_id), INDEX IDX_40902137A4AEAFEA (entreprise_id), INDEX IDX_40902137A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations_liste (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, valide TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE identite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, sexe TINYINT(1) DEFAULT NULL, naissance_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_7E94B58BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_profil (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_49CBEC5FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, competence_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C6AC3544A76ED395 (user_id), INDEX IDX_C6AC354415761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, indicatif INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisations (id INT AUTO_INCREMENT NOT NULL, competence_id INT NOT NULL, experience_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FC5C476D15761DAB (competence_id), INDEX IDX_FC5C476D46E90E27 (experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, adresse_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6494DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, pays_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, code_postal INT NOT NULL, INDEX IDX_43C3D9C3A6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816286C17BC FOREIGN KEY (villes_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE33B92F39 FOREIGN KEY (label_id) REFERENCES competences_liste (id)');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E7033B92F39 FOREIGN KEY (label_id) REFERENCES experiences_liste (id)');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formations ADD CONSTRAINT FK_4090213733B92F39 FOREIGN KEY (label_id) REFERENCES formations_liste (id)');
        $this->addSql('ALTER TABLE formations ADD CONSTRAINT FK_40902137A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('ALTER TABLE formations ADD CONSTRAINT FK_40902137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE identite ADD CONSTRAINT FK_7E94B58BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image_profil ADD CONSTRAINT FK_49CBEC5FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC354415761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D15761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D46E90E27 FOREIGN KEY (experience_id) REFERENCES experiences (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816286C17BC');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CE33B92F39');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CEA76ED395');
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E7033B92F39');
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A4AEAFEA');
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A76ED395');
        $this->addSql('ALTER TABLE formations DROP FOREIGN KEY FK_4090213733B92F39');
        $this->addSql('ALTER TABLE formations DROP FOREIGN KEY FK_40902137A4AEAFEA');
        $this->addSql('ALTER TABLE formations DROP FOREIGN KEY FK_40902137A76ED395');
        $this->addSql('ALTER TABLE identite DROP FOREIGN KEY FK_7E94B58BA76ED395');
        $this->addSql('ALTER TABLE image_profil DROP FOREIGN KEY FK_49CBEC5FA76ED395');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544A76ED395');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC354415761DAB');
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D15761DAB');
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D46E90E27');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3A6E44244');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE competences_liste');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('DROP TABLE experiences');
        $this->addSql('DROP TABLE experiences_liste');
        $this->addSql('DROP TABLE formations');
        $this->addSql('DROP TABLE formations_liste');
        $this->addSql('DROP TABLE identite');
        $this->addSql('DROP TABLE image_profil');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE realisations');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
    }
}
