<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125153044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE referentiels (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiels_groupe_competence (referentiels_id INT NOT NULL, groupe_competence_id INT NOT NULL, INDEX IDX_9577F23AB8F4689C (referentiels_id), INDEX IDX_9577F23A89034830 (groupe_competence_id), PRIMARY KEY(referentiels_id, groupe_competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE referentiels_groupe_competence ADD CONSTRAINT FK_9577F23AB8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiels_groupe_competence ADD CONSTRAINT FK_9577F23A89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referentiels_groupe_competence DROP FOREIGN KEY FK_9577F23AB8F4689C');
        $this->addSql('DROP TABLE referentiels');
        $this->addSql('DROP TABLE referentiels_groupe_competence');
    }
}
