<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121135653 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE UNIQUE INDEX UNIQ_69FDD37777153098 ON s_organisations (code)');
    $this->addSql('ALTER TABLE s_organizations_to_years RENAME INDEX idx_79db1d0b32c8a3de TO IDX_6D8D489132C8A3DE');
    $this->addSql('ALTER TABLE s_organizations_to_years RENAME INDEX idx_79db1d0b40c1fea7 TO IDX_6D8D489140C1FEA7');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_obrashcheniya_files CHANGE image_number image_number VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
    $this->addSql('DROP INDEX UNIQ_69FDD37777153098 ON s_organisations');
    $this->addSql('ALTER TABLE s_organizations_to_years RENAME INDEX idx_6d8d489132c8a3de TO IDX_79DB1D0B32C8A3DE');
    $this->addSql('ALTER TABLE s_organizations_to_years RENAME INDEX idx_6d8d489140c1fea7 TO IDX_79DB1D0B40C1FEA7');
  }
}
