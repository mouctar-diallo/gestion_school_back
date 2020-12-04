<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202154442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_partiel (id INT AUTO_INCREMENT NOT NULL, livrable_partiel_id INT DEFAULT NULL, apprenant_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, delai DATE NOT NULL, data_rendue DATE NOT NULL, INDEX IDX_8572D6AD519178C4 (livrable_partiel_id), INDEX IDX_8572D6ADC5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief (id INT AUTO_INCREMENT NOT NULL, formateurs_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, nom_brief VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, contexte VARCHAR(255) NOT NULL, modalite_pedagogique VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, image LONGBLOB DEFAULT NULL, archive VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, INDEX IDX_1FBB1007FB0881C8 (formateurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_tags (brief_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_D4F170DD757FABFF (brief_id), INDEX IDX_D4F170DD8D7B4FB4 (tags_id), PRIMARY KEY(brief_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_livrable_attendues (brief_id INT NOT NULL, livrable_attendues_id INT NOT NULL, INDEX IDX_3F6ABACF757FABFF (brief_id), INDEX IDX_3F6ABACFC4A87EB9 (livrable_attendues_id), PRIMARY KEY(brief_id, livrable_attendues_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, brief_du_promo_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_DD6198EDC5697D6D (apprenant_id), INDEX IDX_DD6198ED25F536F (brief_du_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_du_promo (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, promos_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_E2D2EBF6757FABFF (brief_id), INDEX IDX_E2D2EBF6CAA392D2 (promos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fil_discution_id INT DEFAULT NULL, formateur_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_67F068BCC85F341A (fil_discution_id), INDEX IDX_67F068BC155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_valides (id INT AUTO_INCREMENT NOT NULL, referentiels_id INT DEFAULT NULL, promos_id INT DEFAULT NULL, apprenant_id INT DEFAULT NULL, competence_id INT DEFAULT NULL, niveau1 VARCHAR(255) NOT NULL, niveau2 VARCHAR(255) NOT NULL, niveau3 VARCHAR(255) NOT NULL, INDEX IDX_9EEA096EB8F4689C (referentiels_id), INDEX IDX_9EEA096ECAA392D2 (promos_id), INDEX IDX_9EEA096EC5697D6D (apprenant_id), INDEX IDX_9EEA096E15761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_brief_groupe (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_4C4C1AA4757FABFF (brief_id), INDEX IDX_4C4C1AA47A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_discution (id INT AUTO_INCREMENT NOT NULL, fil_discution_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_41858334C85F341A (fil_discution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendues (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendues_apprenant (id INT AUTO_INCREMENT NOT NULL, livrable_attendues_id INT DEFAULT NULL, apprenant_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_2652DF84C4A87EB9 (livrable_attendues_id), INDEX IDX_2652DF84C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, delai VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, nbre_rendue INT NOT NULL, nbre_corrige INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel_niveau (livrable_partiel_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_4FEB984B519178C4 (livrable_partiel_id), INDEX IDX_4FEB984BB3E9C81 (niveau_id), PRIMARY KEY(livrable_partiel_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, piece_jointe VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_939F4544757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6AD519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6ADC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007FB0881C8 FOREIGN KEY (formateurs_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_tags ADD CONSTRAINT FK_D4F170DD757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tags ADD CONSTRAINT FK_D4F170DD8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_livrable_attendues ADD CONSTRAINT FK_3F6ABACF757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_livrable_attendues ADD CONSTRAINT FK_3F6ABACFC4A87EB9 FOREIGN KEY (livrable_attendues_id) REFERENCES livrable_attendues (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198EDC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198ED25F536F FOREIGN KEY (brief_du_promo_id) REFERENCES brief_du_promo (id)');
        $this->addSql('ALTER TABLE brief_du_promo ADD CONSTRAINT FK_E2D2EBF6757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE brief_du_promo ADD CONSTRAINT FK_E2D2EBF6CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC85F341A FOREIGN KEY (fil_discution_id) REFERENCES fil_discution (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EB8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiels (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096ECAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096E15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA4757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA47A45358C FOREIGN KEY (groupe_id) REFERENCES groupes (id)');
        $this->addSql('ALTER TABLE fil_discution ADD CONSTRAINT FK_41858334C85F341A FOREIGN KEY (fil_discution_id) REFERENCES apprenant_livrable_partiel (id)');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant ADD CONSTRAINT FK_2652DF84C4A87EB9 FOREIGN KEY (livrable_attendues_id) REFERENCES livrable_attendues (id)');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant ADD CONSTRAINT FK_2652DF84C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrable_partiel_niveau ADD CONSTRAINT FK_4FEB984B519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_partiel_niveau ADD CONSTRAINT FK_4FEB984BB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_discution DROP FOREIGN KEY FK_41858334C85F341A');
        $this->addSql('ALTER TABLE brief_tags DROP FOREIGN KEY FK_D4F170DD757FABFF');
        $this->addSql('ALTER TABLE brief_livrable_attendues DROP FOREIGN KEY FK_3F6ABACF757FABFF');
        $this->addSql('ALTER TABLE brief_du_promo DROP FOREIGN KEY FK_E2D2EBF6757FABFF');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA4757FABFF');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544757FABFF');
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198ED25F536F');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC85F341A');
        $this->addSql('ALTER TABLE brief_livrable_attendues DROP FOREIGN KEY FK_3F6ABACFC4A87EB9');
        $this->addSql('ALTER TABLE livrable_attendues_apprenant DROP FOREIGN KEY FK_2652DF84C4A87EB9');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6AD519178C4');
        $this->addSql('ALTER TABLE livrable_partiel_niveau DROP FOREIGN KEY FK_4FEB984B519178C4');
        $this->addSql('DROP TABLE apprenant_livrable_partiel');
        $this->addSql('DROP TABLE brief');
        $this->addSql('DROP TABLE brief_tags');
        $this->addSql('DROP TABLE brief_livrable_attendues');
        $this->addSql('DROP TABLE brief_apprenant');
        $this->addSql('DROP TABLE brief_du_promo');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competences_valides');
        $this->addSql('DROP TABLE etat_brief_groupe');
        $this->addSql('DROP TABLE fil_discution');
        $this->addSql('DROP TABLE livrable_attendues');
        $this->addSql('DROP TABLE livrable_attendues_apprenant');
        $this->addSql('DROP TABLE livrable_partiel');
        $this->addSql('DROP TABLE livrable_partiel_niveau');
        $this->addSql('DROP TABLE ressource');
    }
}
