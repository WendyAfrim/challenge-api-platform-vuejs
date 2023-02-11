<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119111215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding property uuid to each entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availaibility ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN availaibility.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9694AABFD17F50A6 ON availaibility (uuid)');
        $this->addSql('ALTER TABLE document ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN document.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8698A76D17F50A6 ON document (uuid)');
        $this->addSql('ALTER TABLE property ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN property.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDED17F50A6 ON property (uuid)');
        $this->addSql('ALTER TABLE request ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN request.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B978F9FD17F50A6 ON request (uuid)');
        $this->addSql('ALTER TABLE "user" ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON "user" (uuid)');
        $this->addSql('ALTER TABLE viewing ADD uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN viewing.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5BB4698D17F50A6 ON viewing (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3B978F9FD17F50A6');
        $this->addSql('ALTER TABLE request DROP uuid');
        $this->addSql('DROP INDEX UNIQ_F5BB4698D17F50A6');
        $this->addSql('ALTER TABLE viewing DROP uuid');
        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6');
        $this->addSql('ALTER TABLE "user" DROP uuid');
        $this->addSql('DROP INDEX UNIQ_9694AABFD17F50A6');
        $this->addSql('ALTER TABLE availaibility DROP uuid');
        $this->addSql('DROP INDEX UNIQ_D8698A76D17F50A6');
        $this->addSql('ALTER TABLE document DROP uuid');
        $this->addSql('DROP INDEX UNIQ_8BF21CDED17F50A6');
        $this->addSql('ALTER TABLE property DROP uuid');
    }
}
