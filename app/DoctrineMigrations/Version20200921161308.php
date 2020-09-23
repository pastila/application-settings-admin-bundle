<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200921161308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks CHANGE region_id region_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE valuation valuation INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE text text LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks CHANGE user_id user_id INT NOT NULL, CHANGE region_id region_id INT NOT NULL, CHANGE valuation valuation INT NOT NULL, CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE text text LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
