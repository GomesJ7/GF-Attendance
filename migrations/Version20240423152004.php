<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423152004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenir (formation_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_3C914DFD5200282E (formation_id), INDEX IDX_3C914DFD139DF194 (promotion_id), PRIMARY KEY(formation_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emarger (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, presence TINYINT(1) NOT NULL, alternative VARCHAR(30) DEFAULT NULL, heure_arrivee DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', heure_depart DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7EF5C405613FECDF (session_id), INDEX IDX_7EF5C405FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, certification VARCHAR(30) NOT NULL, specialite VARCHAR(50) NOT NULL, nom_option VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrire (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, INDEX IDX_84CA37A8139DF194 (promotion_id), INDEX IDX_84CA37A8FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom_matiere VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, nom_option VARCHAR(50) NOT NULL, INDEX IDX_5A8600B05200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, annee VARCHAR(9) NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, INDEX IDX_C11D7DD15200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle_classe (id INT AUTO_INCREMENT NOT NULL, nom_salle VARCHAR(30) NOT NULL, adresse VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, matiere_id INT NOT NULL, salle_classe_id INT NOT NULL, intitule VARCHAR(50) NOT NULL, date_session DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', heure_debut TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', heure_fin TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', commentaire VARCHAR(250) DEFAULT NULL, INDEX IDX_D044D5D4FB88E14F (utilisateur_id), INDEX IDX_D044D5D4F46CD258 (matiere_id), INDEX IDX_D044D5D468FAC959 (salle_classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_promotion (session_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_82FF38DB613FECDF (session_id), INDEX IDX_82FF38DB139DF194 (promotion_id), PRIMARY KEY(session_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, civilite TINYINT(1) DEFAULT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFD5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE emarger ADD CONSTRAINT FK_7EF5C405613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE emarger ADD CONSTRAINT FK_7EF5C405FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE inscrire ADD CONSTRAINT FK_84CA37A8139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE inscrire ADD CONSTRAINT FK_84CA37A8FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B05200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD15200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D468FAC959 FOREIGN KEY (salle_classe_id) REFERENCES salle_classe (id)');
        $this->addSql('ALTER TABLE session_promotion ADD CONSTRAINT FK_82FF38DB613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_promotion ADD CONSTRAINT FK_82FF38DB139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFD5200282E');
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFD139DF194');
        $this->addSql('ALTER TABLE emarger DROP FOREIGN KEY FK_7EF5C405613FECDF');
        $this->addSql('ALTER TABLE emarger DROP FOREIGN KEY FK_7EF5C405FB88E14F');
        $this->addSql('ALTER TABLE inscrire DROP FOREIGN KEY FK_84CA37A8139DF194');
        $this->addSql('ALTER TABLE inscrire DROP FOREIGN KEY FK_84CA37A8FB88E14F');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B05200282E');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD15200282E');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4FB88E14F');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4F46CD258');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D468FAC959');
        $this->addSql('ALTER TABLE session_promotion DROP FOREIGN KEY FK_82FF38DB613FECDF');
        $this->addSql('ALTER TABLE session_promotion DROP FOREIGN KEY FK_82FF38DB139DF194');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP TABLE emarger');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE inscrire');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE salle_classe');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_promotion');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
