<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213105626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace property is_accepted by state in Request & adding property state in Viewing';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request ADD state VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE request DROP is_accepted');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request ADD is_accepted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE request DROP state');
    }
}
