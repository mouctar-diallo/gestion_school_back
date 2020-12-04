<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204110209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendues ADD livrable_attendues_apprenants_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendues ADD CONSTRAINT FK_A50807837FA02506 FOREIGN KEY (livrable_attendues_apprenants_id) REFERENCES livrable_attendues_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_A50807837FA02506 ON livrable_attendues (livrable_attendues_apprenants_id)');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant DROP FOREIGN KEY FK_2652DF84C4A87EB9');
        $this->addSql('DROP INDEX IDX_2652DF84C4A87EB9 ON livrable_attendues_apprenant');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant DROP livrable_attendues_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendues DROP FOREIGN KEY FK_A50807837FA02506');
        $this->addSql('DROP INDEX IDX_A50807837FA02506 ON livrable_attendues');
        $this->addSql('ALTER TABLE livrable_attendues DROP livrable_attendues_apprenants_id');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant ADD livrable_attendues_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant ADD CONSTRAINT FK_2652DF84C4A87EB9 FOREIGN KEY (livrable_attendues_id) REFERENCES livrable_attendues (id)');
        $this->addSql('CREATE INDEX IDX_2652DF84C4A87EB9 ON livrable_attendues_apprenant (livrable_attendues_id)');
    }
}
