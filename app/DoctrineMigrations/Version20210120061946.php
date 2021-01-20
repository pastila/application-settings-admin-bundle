<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120061946 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_organisations (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, bitrix_id INT DEFAULT NULL, slug_root VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, name_full VARCHAR(512) DEFAULT NULL, code VARCHAR(255) NOT NULL, address VARCHAR(512) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, status INT NOT NULL, UNIQUE INDEX UNIQ_69FDD377989D9B62 (slug), INDEX IDX_69FDD37798260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_organisations ADD CONSTRAINT FK_69FDD37798260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP TABLE s_organisations');
  }
}
