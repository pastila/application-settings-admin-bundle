<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20201016122440 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_companies DROP email_first, DROP email_second, DROP email_third');
    $this->addSql('ALTER TABLE s_company_branches ADD email_first VARCHAR(256) DEFAULT NULL, ADD email_second VARCHAR(256) DEFAULT NULL, ADD email_third VARCHAR(256) DEFAULT NULL');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_companies ADD email_first VARCHAR(256) DEFAULT NULL COLLATE utf8_unicode_ci, ADD email_second VARCHAR(256) DEFAULT NULL COLLATE utf8_unicode_ci, ADD email_third VARCHAR(256) DEFAULT NULL COLLATE utf8_unicode_ci');
    $this->addSql('ALTER TABLE s_company_branches DROP email_first, DROP email_second, DROP email_third');
  }
}
