<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218152036 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE heure (id INT AUTO_INCREMENT NOT NULL, journee_id INT DEFAULT NULL, plage_horaire TIME NOT NULL, INDEX IDX_1173E8B8CF066148 (journee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heure_user (heure_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E6A47949F2A733EB (heure_id), INDEX IDX_E6A47949A76ED395 (user_id), PRIMARY KEY(heure_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journee (id INT AUTO_INCREMENT NOT NULL, planning_id INT DEFAULT NULL, date DATE NOT NULL, duree_heure INT NOT NULL, duree_minute INT NOT NULL, nombre_personnes INT NOT NULL, INDEX IDX_DC179AED3D865311 (planning_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, event VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, debut DATE DEFAULT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau (id INT AUTO_INCREMENT NOT NULL, annee DATE NOT NULL, classeur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau_ligne (id INT AUTO_INCREMENT NOT NULL, tableau_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, paiement_xfois INT NOT NULL, payee TINYINT(1) NOT NULL, INDEX IDX_DD42B60EB062D5BC (tableau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau_paiement (id INT AUTO_INCREMENT NOT NULL, tableau_ligne_id INT DEFAULT NULL, date_versement DATE NOT NULL, moyen_paiement VARCHAR(255) NOT NULL, somme_versement NUMERIC(5, 2) NOT NULL, INDEX IDX_AD0D350D4692C165 (tableau_ligne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, nom_complet VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_heure (user_id INT NOT NULL, heure_id INT NOT NULL, INDEX IDX_F3B113E0A76ED395 (user_id), INDEX IDX_F3B113E0F2A733EB (heure_id), PRIMARY KEY(user_id, heure_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heure ADD CONSTRAINT FK_1173E8B8CF066148 FOREIGN KEY (journee_id) REFERENCES journee (id)');
        $this->addSql('ALTER TABLE heure_user ADD CONSTRAINT FK_E6A47949F2A733EB FOREIGN KEY (heure_id) REFERENCES heure (id)');
        $this->addSql('ALTER TABLE heure_user ADD CONSTRAINT FK_E6A47949A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE journee ADD CONSTRAINT FK_DC179AED3D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE tableau_ligne ADD CONSTRAINT FK_DD42B60EB062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id)');
        $this->addSql('ALTER TABLE tableau_paiement ADD CONSTRAINT FK_AD0D350D4692C165 FOREIGN KEY (tableau_ligne_id) REFERENCES tableau_ligne (id)');
        $this->addSql('ALTER TABLE user_heure ADD CONSTRAINT FK_F3B113E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_heure ADD CONSTRAINT FK_F3B113E0F2A733EB FOREIGN KEY (heure_id) REFERENCES heure (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE heure_user DROP FOREIGN KEY FK_E6A47949F2A733EB');
        $this->addSql('ALTER TABLE user_heure DROP FOREIGN KEY FK_F3B113E0F2A733EB');
        $this->addSql('ALTER TABLE heure DROP FOREIGN KEY FK_1173E8B8CF066148');
        $this->addSql('ALTER TABLE journee DROP FOREIGN KEY FK_DC179AED3D865311');
        $this->addSql('ALTER TABLE tableau_ligne DROP FOREIGN KEY FK_DD42B60EB062D5BC');
        $this->addSql('ALTER TABLE tableau_paiement DROP FOREIGN KEY FK_AD0D350D4692C165');
        $this->addSql('ALTER TABLE heure_user DROP FOREIGN KEY FK_E6A47949A76ED395');
        $this->addSql('ALTER TABLE user_heure DROP FOREIGN KEY FK_F3B113E0A76ED395');
        $this->addSql('DROP TABLE heure');
        $this->addSql('DROP TABLE heure_user');
        $this->addSql('DROP TABLE journee');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP TABLE tableau_ligne');
        $this->addSql('DROP TABLE tableau_paiement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_heure');
    }
}
