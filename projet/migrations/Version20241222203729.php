<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222203729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la colonne email à la table user';
    }

    public function up(Schema $schema): void
    {
        // Ajouter la colonne email à la table user
        $this->addSql('ALTER TABLE user ADD COLUMN email VARCHAR(180) DEFAULT NULL');
        
        // Autres modifications de schéma nécessaires
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tache CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL, CHANGE eval eval VARCHAR(255) DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la colonne email de la table user
        $this->addSql('ALTER TABLE user DROP COLUMN email');
        
        // Revenir sur les autres modifications de schéma
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tache CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT \'NULL\', CHANGE etat etat VARCHAR(255) DEFAULT \'NULL\', CHANGE eval eval VARCHAR(255) DEFAULT \'NULL\', CHANGE date_fin date_fin DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
    }
}
