<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203065914 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_representatives (id INT AUTO_INCREMENT NOT NULL, branch_id INT NOT NULL, email VARCHAR(256) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, INDEX IDX_93322DC6DCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_representatives ADD CONSTRAINT FK_93322DC6DCD6CC49 FOREIGN KEY (branch_id) REFERENCES s_company_branches (id) ON DELETE RESTRICT');

    // Перенос email-ов из таблицы филиалов в таблицу страховых представителей:
    $this->addSql('INSERT INTO s_representatives (branch_id, email)
                        SELECT scb.id, scb.email_first
                        FROM s_company_branches scb
                        WHERE scb.email_first IS NOT NULL;');
    $this->addSql('INSERT INTO s_representatives (branch_id, email)
                        SELECT scb.id, scb.email_second
                        FROM s_company_branches scb
                        WHERE scb.email_second IS NOT NULL;');
    $this->addSql('INSERT INTO s_representatives (branch_id, email)
                        SELECT scb.id, scb.email_third
                        FROM s_company_branches scb
                        WHERE scb.email_third IS NOT NULL;');

    $this->addSql('ALTER TABLE s_company_branches DROP email_first, DROP email_second, DROP email_third');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP TABLE s_representatives');
    $this->addSql('ALTER TABLE s_company_branches ADD email_first VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD email_second VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD email_third VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
  }
}
