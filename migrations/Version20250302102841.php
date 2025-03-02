<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250302102841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8F114C366B');
        $this->addSql('DROP INDEX IDX_BFD3CE8F114C366B ON localisation');
        $this->addSql('ALTER TABLE localisation CHANGE coord_id coords_id INT NOT NULL');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8F3D0049C3 FOREIGN KEY (coords_id) REFERENCES coords (id)');
        $this->addSql('CREATE INDEX IDX_BFD3CE8F3D0049C3 ON localisation (coords_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8F3D0049C3');
        $this->addSql('DROP INDEX IDX_BFD3CE8F3D0049C3 ON localisation');
        $this->addSql('ALTER TABLE localisation CHANGE coords_id coord_id INT NOT NULL');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8F114C366B FOREIGN KEY (coord_id) REFERENCES coords (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BFD3CE8F114C366B ON localisation (coord_id)');
    }
}
