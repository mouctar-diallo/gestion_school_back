<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202231443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD fil_discution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6ADC85F341A FOREIGN KEY (fil_discution_id) REFERENCES fil_discution (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8572D6ADC85F341A ON apprenant_livrable_partiel (fil_discution_id)');
        $this->addSql('ALTER TABLE fil_discution DROP FOREIGN KEY FK_41858334C85F341A');
        $this->addSql('DROP INDEX UNIQ_41858334C85F341A ON fil_discution');
        $this->addSql('ALTER TABLE fil_discution DROP fil_discution_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6ADC85F341A');
        $this->addSql('DROP INDEX UNIQ_8572D6ADC85F341A ON apprenant_livrable_partiel');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP fil_discution_id');
        $this->addSql('ALTER TABLE fil_discution ADD fil_discution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_discution ADD CONSTRAINT FK_41858334C85F341A FOREIGN KEY (fil_discution_id) REFERENCES apprenant_livrable_partiel (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41858334C85F341A ON fil_discution (fil_discution_id)');
    }
}
