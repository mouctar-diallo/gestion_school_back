<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127085107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupes_formateur (groupes_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_9481F39E305371B (groupes_id), INDEX IDX_9481F39E155D8F51 (formateur_id), PRIMARY KEY(groupes_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes_apprenant (groupes_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_BD1CCBFF305371B (groupes_id), INDEX IDX_BD1CCBFFC5697D6D (apprenant_id), PRIMARY KEY(groupes_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupes_formateur ADD CONSTRAINT FK_9481F39E305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_formateur ADD CONSTRAINT FK_9481F39E155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_apprenant ADD CONSTRAINT FK_BD1CCBFF305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_apprenant ADD CONSTRAINT FK_BD1CCBFFC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupes_formateur');
        $this->addSql('DROP TABLE groupes_apprenant');
    }
}
