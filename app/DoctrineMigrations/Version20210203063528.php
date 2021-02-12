<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203063528 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP INDEX bitrix_id_idx ON s_company_branches');
    $this->addSql('ALTER TABLE s_company_branches DROP name, DROP code, DROP logo_id_from_bitrix, DROP logo_url_from_bitrix, DROP bitrix_id');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_company_branches ADD name VARCHAR(512) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD code VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD logo_id_from_bitrix VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD logo_url_from_bitrix VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD bitrix_id INT DEFAULT NULL');
    $this->addSql('CREATE INDEX bitrix_id_idx ON s_company_branches (bitrix_id)');
  }
}
