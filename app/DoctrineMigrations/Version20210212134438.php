<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212134438 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE background_job (id BIGINT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, clsid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, command VARCHAR(255) DEFAULT NULL, started_at DATETIME DEFAULT NULL, finished_at DATETIME DEFAULT NULL, state VARCHAR(50) DEFAULT NULL, status_code INT DEFAULT NULL, meta_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', progress INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP TABLE background_job');
  }
}
