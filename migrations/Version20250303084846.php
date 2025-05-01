<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303084846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias ADD realisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81B685E551 FOREIGN KEY (realisation_id) REFERENCES realisations (id)');
        $this->addSql('CREATE INDEX IDX_12D2AF81B685E551 ON medias (realisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81B685E551');
        $this->addSql('DROP INDEX IDX_12D2AF81B685E551 ON medias');
        $this->addSql('ALTER TABLE medias DROP realisation_id');
    }
}
