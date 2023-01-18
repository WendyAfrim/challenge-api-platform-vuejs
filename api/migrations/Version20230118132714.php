<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118132714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE availaibility_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE property_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE viewing_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE availaibility (id INT NOT NULL, property_id INT DEFAULT NULL, viewing_id INT DEFAULT NULL, slot TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9694AABF549213EC ON availaibility (property_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9694AABF6FD1AE0A ON availaibility (viewing_id)');
        $this->addSql('COMMENT ON COLUMN availaibility.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availaibility.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE document (id INT NOT NULL, user_document_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, is_valid BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A766A24B1A2 ON document (user_document_id)');
        $this->addSql('COMMENT ON COLUMN document.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN document.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE property (id INT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, country VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, rooms INT DEFAULT NULL, surface SMALLINT DEFAULT NULL, has_balcony BOOLEAN DEFAULT NULL, has_terrace BOOLEAN DEFAULT NULL, has_cave BOOLEAN DEFAULT NULL, has_elevator BOOLEAN DEFAULT NULL, has_parking BOOLEAN DEFAULT NULL, price INT NOT NULL, heat_type VARCHAR(255) DEFAULT NULL, is_furnished BOOLEAN DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8BF21CDE7E3C61F9 ON property (owner_id)');
        $this->addSql('COMMENT ON COLUMN property.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN property.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, lodger_id INT DEFAULT NULL, property_id INT DEFAULT NULL, is_accepted BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9F36790F15 ON request (lodger_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F549213EC ON request (property_id)');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, situation VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE viewing (id INT NOT NULL, agent_id INT DEFAULT NULL, lodger_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5BB46983414710B ON viewing (agent_id)');
        $this->addSql('CREATE INDEX IDX_F5BB469836790F15 ON viewing (lodger_id)');
        $this->addSql('COMMENT ON COLUMN viewing.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN viewing.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT FK_9694AABF549213EC FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availaibility ADD CONSTRAINT FK_9694AABF6FD1AE0A FOREIGN KEY (viewing_id) REFERENCES viewing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A766A24B1A2 FOREIGN KEY (user_document_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F36790F15 FOREIGN KEY (lodger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F549213EC FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE viewing ADD CONSTRAINT FK_F5BB46983414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE viewing ADD CONSTRAINT FK_F5BB469836790F15 FOREIGN KEY (lodger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD salary INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD income_source VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE availaibility_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE property_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE viewing_id_seq CASCADE');
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT FK_9694AABF549213EC');
        $this->addSql('ALTER TABLE availaibility DROP CONSTRAINT FK_9694AABF6FD1AE0A');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A766A24B1A2');
        $this->addSql('ALTER TABLE property DROP CONSTRAINT FK_8BF21CDE7E3C61F9');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F36790F15');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F549213EC');
        $this->addSql('ALTER TABLE viewing DROP CONSTRAINT FK_F5BB46983414710B');
        $this->addSql('ALTER TABLE viewing DROP CONSTRAINT FK_F5BB469836790F15');
        $this->addSql('DROP TABLE availaibility');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE viewing');
    }
}
