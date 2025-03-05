<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305163744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assignation (id INT AUTO_INCREMENT NOT NULL, tache_id INT NOT NULL, date_de_debut DATE NOT NULL, date_de_fin DATE NOT NULL, INDEX IDX_D2A03CE0D2235D39 (tache_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignation_employe (assignation_id INT NOT NULL, employe_id INT NOT NULL, INDEX IDX_8A76E5546A86CF55 (assignation_id), INDEX IDX_8A76E5541B65292 (employe_id), PRIMARY KEY(assignation_id, employe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE0D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE assignation_employe ADD CONSTRAINT FK_8A76E5546A86CF55 FOREIGN KEY (assignation_id) REFERENCES assignation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_employe ADD CONSTRAINT FK_8A76E5541B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE0D2235D39');
        $this->addSql('ALTER TABLE assignation_employe DROP FOREIGN KEY FK_8A76E5546A86CF55');
        $this->addSql('ALTER TABLE assignation_employe DROP FOREIGN KEY FK_8A76E5541B65292');
        $this->addSql('DROP TABLE assignation');
        $this->addSql('DROP TABLE assignation_employe');
    }
}
