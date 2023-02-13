<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212234344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop viewing_id in Availaibility & Adding availaibility_id in Viewing';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT fk_9694aabf6fd1ae0a');
        $this->addSql('DROP INDEX uniq_9694aabf6fd1ae0a');
        $this->addSql('ALTER TABLE availaibility DROP viewing_id');
        $this->addSql('ALTER TABLE viewing ADD availaibility_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE viewing ADD CONSTRAINT FK_F5BB4698BC97FB5 FOREIGN KEY (availaibility_id) REFERENCES availaibility (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5BB4698BC97FB5 ON viewing (availaibility_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility ADD viewing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT fk_9694aabf6fd1ae0a FOREIGN KEY (viewing_id) REFERENCES viewing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_9694aabf6fd1ae0a ON availaibility (viewing_id)');
        $this->addSql('ALTER TABLE viewing DROP CONSTRAINT FK_F5BB4698BC97FB5');
        $this->addSql('DROP INDEX UNIQ_F5BB4698BC97FB5');
        $this->addSql('ALTER TABLE viewing DROP availaibility_id');
    }
}
