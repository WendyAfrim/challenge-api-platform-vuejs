<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207071356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP CONSTRAINT fk_d8698a7693cb796c');
        $this->addSql('DROP INDEX idx_d8698a7693cb796c');
        $this->addSql('ALTER TABLE document ADD file_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE document DROP file_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document DROP file_path');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT fk_d8698a7693cb796c FOREIGN KEY (file_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d8698a7693cb796c ON document (file_id)');
    }
}
