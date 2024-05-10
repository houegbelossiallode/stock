<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305134114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE code code VARCHAR(8) NOT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prix prix VARCHAR(50) NOT NULL, CHANGE quantite quantite INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD matricule VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE code code VARCHAR(8) DEFAULT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prix prix VARCHAR(50) DEFAULT NULL, CHANGE quantite quantite INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` DROP matricule');
    }
}
