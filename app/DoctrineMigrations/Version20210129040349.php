<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129040349 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_organizations (code INT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, name_full VARCHAR(512) NOT NULL, address VARCHAR(512) NOT NULL, published TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_FC6596A498260155 (region_id), PRIMARY KEY(code)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE s_organization_chief_medical_officers (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8F4D31BA32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE s_organization_years (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, year INT NOT NULL, INDEX IDX_38FF0D6E32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_organizations ADD CONSTRAINT FK_FC6596A498260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');
    $this->addSql('ALTER TABLE s_organization_chief_medical_officers ADD CONSTRAINT FK_8F4D31BA32C8A3DE FOREIGN KEY (organization_id) REFERENCES s_organizations (code)');
    $this->addSql('ALTER TABLE s_organization_years ADD CONSTRAINT FK_38FF0D6E32C8A3DE FOREIGN KEY (organization_id) REFERENCES s_organizations (code)');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_organization_chief_medical_officers DROP FOREIGN KEY FK_8F4D31BA32C8A3DE');
    $this->addSql('ALTER TABLE s_organization_years DROP FOREIGN KEY FK_38FF0D6E32C8A3DE');
    $this->addSql('DROP TABLE s_organizations');
    $this->addSql('DROP TABLE s_organization_chief_medical_officers');
    $this->addSql('DROP TABLE s_organization_years');
  }
}
