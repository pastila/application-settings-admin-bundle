<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121135135 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_organizations_to_years (organization_id INT NOT NULL, year_id INT NOT NULL, INDEX IDX_79DB1D0B32C8A3DE (organization_id), INDEX IDX_79DB1D0B40C1FEA7 (year_id), PRIMARY KEY(organization_id, year_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE s_organisation_years (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_organizations_to_years ADD CONSTRAINT FK_79DB1D0B32C8A3DE FOREIGN KEY (organization_id) REFERENCES s_organisations (id)');
    $this->addSql('ALTER TABLE s_organizations_to_years ADD CONSTRAINT FK_79DB1D0B40C1FEA7 FOREIGN KEY (year_id) REFERENCES s_organisation_years (id)');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_organizations_to_years DROP FOREIGN KEY FK_79DB1D0B40C1FEA7');
    $this->addSql('DROP TABLE s_organizations_to_years');
    $this->addSql('DROP TABLE s_organisation_years');
    $this->addSql('ALTER TABLE s_obrashcheniya_files CHANGE image_number image_number VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
  }
}
