<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028115305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, file_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, message LONGTEXT NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_67F068BCC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, INDEX IDX_D8698A7612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employer (id INT AUTO_INCREMENT NOT NULL, groupe_id INT DEFAULT NULL, matricule VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DE4CF0667A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, worker_id INT DEFAULT NULL, worker_group_id INT DEFAULT NULL, referance VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, debut DATE NOT NULL, estimation INT DEFAULT NULL, pourcentage DOUBLE PRECISION DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_938720756B20BA36 (worker_id), INDEX IDX_93872075F60D0B11 (worker_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, file_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF0667A45358C FOREIGN KEY (groupe_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720756B20BA36 FOREIGN KEY (worker_id) REFERENCES employer (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075F60D0B11 FOREIGN KEY (worker_group_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC33F7837');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7612469DE2');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF0667A45358C');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720756B20BA36');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075F60D0B11');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video');
    }
}
