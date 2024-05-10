<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213154930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article CHANGE code code VARCHAR(8) NOT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prix prix VARCHAR(50) NOT NULL, CHANGE quantite quantite INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E666C6E55B5 ON article (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP INDEX UNIQ_23A0E666C6E55B5 ON article');
        $this->addSql('ALTER TABLE article CHANGE code code VARCHAR(8) DEFAULT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prix prix VARCHAR(50) DEFAULT NULL, CHANGE quantite quantite INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
