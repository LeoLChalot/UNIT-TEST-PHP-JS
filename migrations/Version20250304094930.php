<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304094930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache_employe (tache_id INT NOT NULL, employe_id INT NOT NULL, INDEX IDX_3252ED0CD2235D39 (tache_id), INDEX IDX_3252ED0C1B65292 (employe_id), PRIMARY KEY(tache_id, employe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache_employe ADD CONSTRAINT FK_3252ED0CD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_employe ADD CONSTRAINT FK_3252ED0C1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache_employe DROP FOREIGN KEY FK_3252ED0CD2235D39');
        $this->addSql('ALTER TABLE tache_employe DROP FOREIGN KEY FK_3252ED0C1B65292');
        $this->addSql('DROP TABLE tache_employe');
    }
}
