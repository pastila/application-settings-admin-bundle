<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113063002 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(256) NOT NULL, announce LONGTEXT NOT NULL, text LONGTEXT DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, teaser_image_options JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', is_external TINYINT(1) DEFAULT \'0\' NOT NULL, external_url VARCHAR(256) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP TABLE news');
  }
}
