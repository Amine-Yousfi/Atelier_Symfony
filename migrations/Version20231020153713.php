<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020153713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit ADD categorie_ref VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC273AD8FFA2 FOREIGN KEY (categorie_ref) REFERENCES categorie (ref)');
        $this->addSql('CREATE INDEX IDX_29A5EC273AD8FFA2 ON produit (categorie_ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC273AD8FFA2');
        $this->addSql('DROP INDEX IDX_29A5EC273AD8FFA2 ON produit');
        $this->addSql('ALTER TABLE produit DROP categorie_ref');
    }
}
