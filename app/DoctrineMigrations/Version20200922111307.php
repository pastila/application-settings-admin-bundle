<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200922111307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks DROP FOREIGN KEY FK_E7592224EFCD46B9');
        $this->addSql('DROP INDEX IDX_E7592224EFCD46B9 ON s_company_feedbacks');
        $this->addSql('ALTER TABLE s_company_feedbacks DROP company_branch_id');
        $this->addSql('ALTER TABLE s_companies ADD valuation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE s_company_branches ADD valuation INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_companies DROP valuation');
        $this->addSql('ALTER TABLE s_company_branches DROP valuation');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD company_branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E7592224EFCD46B9 FOREIGN KEY (company_branch_id) REFERENCES s_company_branches (id)');
        $this->addSql('CREATE INDEX IDX_E7592224EFCD46B9 ON s_company_feedbacks (company_branch_id)');
    }
}
