<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213212126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, quantity VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_888A2A4C7294869C (article_id), INDEX IDX_888A2A4C19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C7294869C FOREIGN KEY (article_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C19EB6921 FOREIGN KEY (client_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE client CHANGE telephone telephone VARCHAR(8) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C7294869C');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C19EB6921');
        $this->addSql('DROP TABLE vente');
        $this->addSql('ALTER TABLE client CHANGE telephone telephone VARCHAR(255) NOT NULL');
    }
}
