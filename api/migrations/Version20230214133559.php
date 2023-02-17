<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214133559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT fk_9694aabf549213ec');
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT fk_9694aabf36790f15');
        $this->addSql('DROP INDEX idx_9694aabf36790f15');
        $this->addSql('DROP INDEX idx_9694aabf549213ec');
        $this->addSql('ALTER TABLE availaibility ADD request_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE availaibility DROP property_id');
        $this->addSql('ALTER TABLE availaibility DROP lodger_id');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT FK_9694AABF427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9694AABF427EB8A5 ON availaibility (request_id)');
        $this->addSql('ALTER TABLE availaibility ADD state VARCHAR(255) DEFAULT NULL;');
        $this->addSql('ALTER TABLE viewing ALTER availaibility_id SET NOT NULL;');
        $this->addSql('ALTER TABLE availaibility ADD viewing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT FK_9694AABF6FD1AE0A FOREIGN KEY (viewing_id) REFERENCES viewing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9694AABF6FD1AE0A ON availaibility (viewing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT FK_9694AABF427EB8A5');
        $this->addSql('DROP INDEX IDX_9694AABF427EB8A5');
        $this->addSql('ALTER TABLE availaibility ADD lodger_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE availaibility RENAME COLUMN request_id TO property_id');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT fk_9694aabf549213ec FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT fk_9694aabf36790f15 FOREIGN KEY (lodger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9694aabf36790f15 ON availaibility (lodger_id)');
        $this->addSql('CREATE INDEX idx_9694aabf549213ec ON availaibility (property_id)');
        $this->addSql('ALTER TABLE availaibility DROP state ');
        $this->addSql('ALTER TABLE viewing ALTER availaibility_id SET NULL;');
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT FK_9694AABF6FD1AE0A');
        $this->addSql('DROP INDEX UNIQ_9694AABF6FD1AE0A');
        $this->addSql('ALTER TABLE availaibility DROP viewing_id');
    }
}
