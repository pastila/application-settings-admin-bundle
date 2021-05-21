<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517085239 extends AbstractMigration
{
  public function up (Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE patients (id INT AUTO_INCREMENT NOT NULL, insurance_company_id INT NOT NULL, region_id INT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, birth_date DATE DEFAULT NULL, insurance_policy_number VARCHAR(16) DEFAULT NULL, INDEX IDX_2CCC2E2CECB24509 (insurance_company_id), INDEX IDX_2CCC2E2C98260155 (region_id), INDEX IDX_2CCC2E2CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE s_oms_charge_complaint_documents (id INT AUTO_INCREMENT NOT NULL, oms_charge_complaint_id INT NOT NULL, file VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, file_extension VARCHAR(10) DEFAULT NULL, original_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_EB18FD81B4E3EE72 (oms_charge_complaint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2CECB24509 FOREIGN KEY (insurance_company_id) REFERENCES s_companies (id)');
    $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2C98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');
    $this->addSql('ALTER TABLE patients ADD CONSTRAINT FK_2CCC2E2CA76ED395 FOREIGN KEY (user_id) REFERENCES s_users (id)');
    $this->addSql('ALTER TABLE s_oms_charge_complaint_documents ADD CONSTRAINT FK_EB18FD81B4E3EE72 FOREIGN KEY (oms_charge_complaint_id) REFERENCES s_oms_charge_complaints (id)');
    $this->addSql('ALTER TABLE s_oms_charge_complaints ADD patient_id INT NULL');
    $this->addSql('ALTER TABLE s_oms_charge_complaints ADD CONSTRAINT FK_43959F726B899279 FOREIGN KEY (patient_id) REFERENCES patients (id)');
  }

  public function down (Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_oms_charge_complaints DROP FOREIGN KEY FK_43959F726B899279');
    $this->addSql('DROP TABLE patients');
    $this->addSql('DROP TABLE s_oms_charge_complaint_documents');
    $this->addSql('ALTER TABLE s_oms_charge_complaints DROP patient_id');
  }
}
