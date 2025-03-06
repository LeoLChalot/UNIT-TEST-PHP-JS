<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306090434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier ADD numero_de_la_voie VARCHAR(255) NOT NULL, ADD type_de_voie VARCHAR(255) NOT NULL, ADD libelle_de_la_voie VARCHAR(255) NOT NULL, ADD code_postal INT NOT NULL, ADD ville VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier DROP numero_de_la_voie, DROP type_de_voie, DROP libelle_de_la_voie, DROP code_postal, DROP ville');
    }
}
