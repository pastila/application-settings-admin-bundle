<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200921160913 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_company_branches (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, region_id INT DEFAULT NULL, name VARCHAR(512) NOT NULL, type VARCHAR(512) DEFAULT NULL, code VARCHAR(256) DEFAULT NULL, INDEX IDX_EB2CC66C979B1AD6 (company_id), INDEX IDX_EB2CC66C98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_company_branches ADD CONSTRAINT FK_EB2CC66C979B1AD6 FOREIGN KEY (company_id) REFERENCES s_companies (id) ON DELETE RESTRICT');
    $this->addSql('ALTER TABLE s_company_branches ADD CONSTRAINT FK_EB2CC66C98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');
    $this->addSql('ALTER TABLE s_company_feedbacks ADD company_branch_id INT DEFAULT NULL, CHANGE company_id company_id INT DEFAULT NULL, CHANGE head title VARCHAR(255) NOT NULL');
    $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E7592224EFCD46B9 FOREIGN KEY (company_branch_id) REFERENCES s_company_branches (id) ON DELETE RESTRICT');
    $this->addSql('CREATE INDEX IDX_E7592224EFCD46B9 ON s_company_feedbacks (company_branch_id)');
    $this->addSql('ALTER TABLE s_companies DROP FOREIGN KEY FK_2015E64F98260155');
    $this->addSql('DROP INDEX IDX_2015E64F98260155 ON s_companies');
    $this->addSql('ALTER TABLE s_companies DROP region_id, DROP type, DROP director');
    $this->addSql('ALTER TABLE s_company_feedback_comments ADD feedback_id INT DEFAULT NULL');
    $this->addSql('ALTER TABLE s_company_feedback_comments ADD CONSTRAINT FK_AF30E174D249A887 FOREIGN KEY (feedback_id) REFERENCES s_company_feedbacks (id) ON DELETE RESTRICT');
    $this->addSql('CREATE INDEX IDX_AF30E174D249A887 ON s_company_feedback_comments (feedback_id)');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_company_feedbacks DROP FOREIGN KEY FK_E7592224EFCD46B9');
    $this->addSql('DROP TABLE s_company_branches');
    $this->addSql('ALTER TABLE s_companies ADD region_id INT NOT NULL, ADD type VARCHAR(512) DEFAULT NULL COLLATE utf8_unicode_ci, ADD director VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    $this->addSql('ALTER TABLE s_companies ADD CONSTRAINT FK_2015E64F98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');
    $this->addSql('CREATE INDEX IDX_2015E64F98260155 ON s_companies (region_id)');
    $this->addSql('ALTER TABLE s_company_feedback_comments DROP FOREIGN KEY FK_AF30E174D249A887');
    $this->addSql('DROP INDEX IDX_AF30E174D249A887 ON s_company_feedback_comments');
    $this->addSql('ALTER TABLE s_company_feedback_comments DROP feedback_id');
    $this->addSql('DROP INDEX IDX_E7592224EFCD46B9 ON s_company_feedbacks');
    $this->addSql('ALTER TABLE s_company_feedbacks DROP company_branch_id, CHANGE company_id company_id INT NOT NULL, CHANGE title head VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
  }
}