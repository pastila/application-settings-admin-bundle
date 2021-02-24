<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212085428 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_users DROP login, CHANGE email email VARCHAR(180) NOT NULL, CHANGE username username VARCHAR(180) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(180) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(180) NOT NULL');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_6F95FD1E92FC23A8 ON s_users (username_canonical)');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_6F95FD1EA0D96FBF ON s_users (email_canonical)');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_6F95FD1EC05FB297 ON s_users (confirmation_token)');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('DROP INDEX UNIQ_6F95FD1E92FC23A8 ON s_users');
    $this->addSql('DROP INDEX UNIQ_6F95FD1EA0D96FBF ON s_users');
    $this->addSql('DROP INDEX UNIQ_6F95FD1EC05FB297 ON s_users');
    $this->addSql('ALTER TABLE s_users ADD login VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE username_canonical username_canonical VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE email email VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE email_canonical email_canonical VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
  }
}
