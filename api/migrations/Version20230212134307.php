<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212134307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding lodger in Availaibility entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility ADD lodger_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT FK_9694AABF36790F15 FOREIGN KEY (lodger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9694AABF36790F15 ON availaibility (lodger_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT FK_9694AABF36790F15');
        $this->addSql('DROP INDEX IDX_9694AABF36790F15');
        $this->addSql('ALTER TABLE availaibility DROP lodger_id');
    }
}
