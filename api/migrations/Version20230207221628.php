<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207221628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding properties level & photos in Property entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132549213EC FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14D43132549213EC ON media_object (property_id)');
        $this->addSql('ALTER TABLE property ADD level SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD city VARCHAR(255) DEFAULT NULL;');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D43132549213EC');
        $this->addSql('DROP INDEX IDX_14D43132549213EC');
        $this->addSql('ALTER TABLE media_object DROP property_id');
        $this->addSql('ALTER TABLE property DROP level');
/*        $this->addSql('ALTER TABLE property DROP city');*/
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET DEFAULT false');
    }
}
