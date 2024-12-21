<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220235900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache_vote (id INT AUTO_INCREMENT NOT NULL, tache_id INT NOT NULL, user_id INT NOT NULL, rating INT NOT NULL, INDEX IDX_2823E122D2235D39 (tache_id), INDEX IDX_2823E122A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache_vote ADD CONSTRAINT FK_2823E122D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE tache_vote ADD CONSTRAINT FK_2823E122A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tache ADD votes_one_star INT NOT NULL, ADD votes_two_stars INT NOT NULL, ADD votes_three_stars INT NOT NULL, CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL, CHANGE eval eval VARCHAR(255) DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache_vote DROP FOREIGN KEY FK_2823E122D2235D39');
        $this->addSql('ALTER TABLE tache_vote DROP FOREIGN KEY FK_2823E122A76ED395');
        $this->addSql('DROP TABLE tache_vote');
        $this->addSql('ALTER TABLE video CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE article CHANGE file_name file_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tache DROP votes_one_star, DROP votes_two_stars, DROP votes_three_stars, CHANGE pourcentage pourcentage DOUBLE PRECISION DEFAULT \'NULL\', CHANGE etat etat VARCHAR(255) DEFAULT \'NULL\', CHANGE eval eval VARCHAR(255) DEFAULT \'NULL\', CHANGE date_fin date_fin DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
