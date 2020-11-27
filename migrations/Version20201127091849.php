<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127091849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promos ADD referentiels_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705B8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiels (id)');
        $this->addSql('CREATE INDEX IDX_31D1F705B8F4689C ON promos (referentiels_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705B8F4689C');
        $this->addSql('DROP INDEX IDX_31D1F705B8F4689C ON promos');
        $this->addSql('ALTER TABLE promos DROP referentiels_id');
    }
}
