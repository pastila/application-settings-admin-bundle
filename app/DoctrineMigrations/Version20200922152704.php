<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200922152704 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks DROP FOREIGN KEY FK_E7592224979B1AD6');
        $this->addSql('DROP INDEX IDX_E7592224979B1AD6 ON s_company_feedbacks');
        $this->addSql('ALTER TABLE s_company_feedbacks DROP company_id');
        $this->addSql('ALTER TABLE s_company_feedback_comments CHANGE user_id user_id INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedback_comments CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E7592224979B1AD6 FOREIGN KEY (company_id) REFERENCES s_companies (id)');
        $this->addSql('CREATE INDEX IDX_E7592224979B1AD6 ON s_company_feedbacks (company_id)');
    }
}
