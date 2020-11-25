<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125091418 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql("INSERT INTO settings (name, value) VALUES ('social_telegram', null)");
    $this->addSql("INSERT INTO settings (name, value) VALUES ('social_instagram', null)");
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql("DELETE FROM settings WHERE name='social_telegram'");
    $this->addSql("DELETE FROM settings WHERE name='social_instagram'");
  }
}
