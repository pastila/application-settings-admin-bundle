<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210524112651 extends AbstractMigration
{
  public function up (Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_oms_charge_complaint_patients (id INT AUTO_INCREMENT NOT NULL, insurance_company_id INT NOT NULL, region_id INT NOT NULL, oms_charge_complaint_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, birth_date DATE DEFAULT NULL, insurance_policy_number VARCHAR(16) DEFAULT NULL, INDEX IDX_ED046ACAECB24509 (insurance_company_id), INDEX IDX_ED046ACA98260155 (region_id), UNIQUE INDEX UNIQ_ED046ACAB4E3EE72 (oms_charge_complaint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_oms_charge_complaint_patients ADD CONSTRAINT FK_ED046ACAECB24509 FOREIGN KEY (insurance_company_id) REFERENCES s_companies (id)');
    $this->addSql('ALTER TABLE s_oms_charge_complaint_patients ADD CONSTRAINT FK_ED046ACA98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');
    $this->addSql('ALTER TABLE s_oms_charge_complaint_patients ADD CONSTRAINT FK_ED046ACAB4E3EE72 FOREIGN KEY (oms_charge_complaint_id) REFERENCES s_oms_charge_complaints (id) ON DELETE CASCADE');
  }

  public function down (Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP TABLE s_oms_charge_complaint_patients');
  }
}
