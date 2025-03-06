<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306081936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assignation (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, tache_id INT NOT NULL, date_de_debut DATE NOT NULL, date_de_fin DATE NOT NULL, INDEX IDX_D2A03CE01B65292 (employe_id), INDEX IDX_D2A03CE0D2235D39 (tache_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chantier (id INT AUTO_INCREMENT NOT NULL, chef_de_chantier_id INT DEFAULT NULL, client_id INT NOT NULL, date_de_debut DATE NOT NULL, date_de_fin DATE NOT NULL, nom VARCHAR(255) NOT NULL, date_tache_suivante DATE NOT NULL, UNIQUE INDEX UNIQ_636F27F6A6C7D29B (chef_de_chantier_id), INDEX IDX_636F27F619EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, metier_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, est_chef_de_chantier TINYINT(1) NOT NULL, disponible TINYINT(1) NOT NULL, INDEX IDX_F804D3B9ED16FA20 (metier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metier (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, chantier_id INT NOT NULL, description LONGTEXT DEFAULT NULL, statut VARCHAR(255) NOT NULL, duree DOUBLE PRECISION NOT NULL, date_de_fin DATE NOT NULL, date_de_debut DATE NOT NULL, INDEX IDX_93872075D0C0049D (chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache_employe (tache_id INT NOT NULL, employe_id INT NOT NULL, INDEX IDX_3252ED0CD2235D39 (tache_id), INDEX IDX_3252ED0C1B65292 (employe_id), PRIMARY KEY(tache_id, employe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE01B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE0D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F6A6C7D29B FOREIGN KEY (chef_de_chantier_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9ED16FA20 FOREIGN KEY (metier_id) REFERENCES metier (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE tache_employe ADD CONSTRAINT FK_3252ED0CD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_employe ADD CONSTRAINT FK_3252ED0C1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE01B65292');
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE0D2235D39');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F6A6C7D29B');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F619EB6921');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9ED16FA20');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075D0C0049D');
        $this->addSql('ALTER TABLE tache_employe DROP FOREIGN KEY FK_3252ED0CD2235D39');
        $this->addSql('ALTER TABLE tache_employe DROP FOREIGN KEY FK_3252ED0C1B65292');
        $this->addSql('DROP TABLE assignation');
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE tache_employe');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
