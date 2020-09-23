<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200922055256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks ADD moderation_status INT NOT NULL, CHANGE text text LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE s_company_branches ADD kpp VARCHAR(256) DEFAULT NULL');
        $this->addSql('ALTER TABLE s_regions CHANGE code code VARCHAR(512) NOT NULL, CHANGE name name VARCHAR(512) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C2AAC595E237E06 ON s_regions (name)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_branches DROP kpp');
        $this->addSql('ALTER TABLE s_company_feedbacks DROP moderation_status, CHANGE text text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_9C2AAC595E237E06 ON s_regions');
        $this->addSql('ALTER TABLE s_regions CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE code code INT NOT NULL');
    }
}
