<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503123328 extends AbstractMigration
{
  public function up(Schema $schema) : void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('RENAME TABLE s_organizations TO s_medical_organizations');
    $this->addSql('ALTER TABLE s_medical_organizations ADD CONSTRAINT FK_636BA74D98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');

    $this->addSql('CREATE TABLE s_oms_charge_complaints (id VARCHAR(255) NOT NULL, region_id INT DEFAULT NULL, medical_organization_id INT DEFAULT NULL, year INT NOT NULL, urgent TINYINT(1) DEFAULT NULL, disease VARCHAR(255) NOT NULL, paid_at DATETIME DEFAULT NULL, status INT NOT NULL, INDEX IDX_43959F7298260155 (region_id), INDEX IDX_43959F72C43F5C2E (medical_organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

    $this->addSql('ALTER TABLE s_oms_charge_complaints ADD CONSTRAINT FK_43959F7298260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');
    $this->addSql('ALTER TABLE s_oms_charge_complaints ADD CONSTRAINT FK_43959F72C43F5C2E FOREIGN KEY (medical_organization_id) REFERENCES s_medical_organizations (code)');

    $this->addSql('ALTER TABLE s_organization_years DROP FOREIGN KEY FK_38FF0D6E4E1C0E05');
    $this->addSql('ALTER TABLE s_organization_years ADD CONSTRAINT FK_38FF0D6E4E1C0E05 FOREIGN KEY (organization_code) REFERENCES s_medical_organizations (code)');
    $this->addSql('ALTER TABLE s_organization_chief_medical_officers DROP FOREIGN KEY FK_8F4D31BA32C8A3DE');
    $this->addSql('ALTER TABLE s_organization_chief_medical_officers ADD CONSTRAINT FK_8F4D31BA32C8A3DE FOREIGN KEY (organization_id) REFERENCES s_medical_organizations (code)');
  }

  public function down(Schema $schema) : void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_oms_charge_complaints DROP FOREIGN KEY FK_43959F72C43F5C2E');

    $this->addSql('RENAME TABLE s_medical_organizations TO s_organizations');

    $this->addSql('ALTER TABLE s_organizations ADD CONSTRAINT FK_FC6596A498260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');

    $this->addSql('DROP TABLE s_oms_charge_complaints');

    $this->addSql('ALTER TABLE s_organization_chief_medical_officers DROP FOREIGN KEY FK_8F4D31BA32C8A3DE');
    $this->addSql('ALTER TABLE s_organization_chief_medical_officers ADD CONSTRAINT FK_8F4D31BA32C8A3DE FOREIGN KEY (organization_id) REFERENCES s_organizations (code)');
    $this->addSql('ALTER TABLE s_organization_years DROP FOREIGN KEY FK_38FF0D6E4E1C0E05');
    $this->addSql('ALTER TABLE s_organization_years ADD CONSTRAINT FK_38FF0D6E4E1C0E05 FOREIGN KEY (organization_code) REFERENCES s_organizations (code)');
  }
}
