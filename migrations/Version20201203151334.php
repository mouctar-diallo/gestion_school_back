<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203151334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_partiel ADD brief_du_promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C525F536F FOREIGN KEY (brief_du_promo_id) REFERENCES brief_du_promo (id)');
        $this->addSql('CREATE INDEX IDX_37F072C525F536F ON livrable_partiel (brief_du_promo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_partiel DROP FOREIGN KEY FK_37F072C525F536F');
        $this->addSql('DROP INDEX IDX_37F072C525F536F ON livrable_partiel');
        $this->addSql('ALTER TABLE livrable_partiel DROP brief_du_promo_id');
    }
}
