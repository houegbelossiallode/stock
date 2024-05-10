<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213230500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C19EB6921');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C7294869C');
        $this->addSql('DROP INDEX IDX_888A2A4C7294869C ON vente');
        $this->addSql('DROP INDEX IDX_888A2A4C19EB6921 ON vente');
        $this->addSql('ALTER TABLE vente DROP article_id, DROP client_id, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente ADD article_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C19EB6921 FOREIGN KEY (client_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C7294869C FOREIGN KEY (article_id) REFERENCES vente (id)');
        $this->addSql('CREATE INDEX IDX_888A2A4C7294869C ON vente (article_id)');
        $this->addSql('CREATE INDEX IDX_888A2A4C19EB6921 ON vente (client_id)');
    }
}
