<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201220841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reunion (id INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, datefin DATETIME NOT NULL, liste_inviter LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tache CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reunion');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tache CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT \'NULL\', CHANGE etat etat VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
