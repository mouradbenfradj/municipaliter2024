<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209024706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reunion_user (reunion_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_80D64F6D4E9B7368 (reunion_id), INDEX IDX_80D64F6DA76ED395 (user_id), PRIMARY KEY(reunion_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reunion_user ADD CONSTRAINT FK_80D64F6D4E9B7368 FOREIGN KEY (reunion_id) REFERENCES reunion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reunion_user ADD CONSTRAINT FK_80D64F6DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC33F7837');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7612469DE2');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF0667A45358C');
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F71F55203D');
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7A76ED395');
        $this->addSql('ALTER TABLE reunion_user DROP FOREIGN KEY FK_80D64F6D4E9B7368');
        $this->addSql('ALTER TABLE reunion_user DROP FOREIGN KEY FK_80D64F6DA76ED395');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720756B20BA36');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075F60D0B11');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BA76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('DROP TABLE reunion');
        $this->addSql('DROP TABLE reunion_user');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video');
    }
}
