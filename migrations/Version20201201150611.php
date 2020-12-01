<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201150611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE groupe_competence ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE groupe_tags ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE groupes ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE promos ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE referentiels ADD archive INT DEFAULT 0');
        $this->addSql('ALTER TABLE tags ADD archive INT DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP archive');
        $this->addSql('ALTER TABLE groupe_competence DROP archive');
        $this->addSql('ALTER TABLE groupe_tags DROP archive');
        $this->addSql('ALTER TABLE groupes DROP archive');
        $this->addSql('ALTER TABLE promos DROP archive');
        $this->addSql('ALTER TABLE referentiels DROP archive');
        $this->addSql('ALTER TABLE tags DROP archive');
    }
}
