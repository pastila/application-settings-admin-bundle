<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210092858 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_users ADD username VARCHAR(180) DEFAULT NULL, ADD username_canonical VARCHAR(180) DEFAULT NULL, ADD email_canonical VARCHAR(180) DEFAULT NULL, ADD enabled TINYINT(1) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) DEFAULT NULL, ADD password_requested_at DATETIME DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD phone VARCHAR(50) DEFAULT NULL, ADD birthdate DATE DEFAULT NULL, ADD insurance_policy_number VARCHAR(50) DEFAULT NULL, ADD salt VARCHAR(255) DEFAULT NULL, ADD terms_and_conditions_accepted TINYINT(1) DEFAULT \'0\' NOT NULL, DROP bitrix_id, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE middle_name middle_name VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(180) DEFAULT NULL');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_users ADD bitrix_id INT DEFAULT NULL, DROP username, DROP username_canonical, DROP email_canonical, DROP enabled, DROP password, DROP last_login, DROP confirmation_token, DROP password_requested_at, DROP roles, DROP phone, DROP birthdate, DROP insurance_policy_number, DROP terms_and_conditions_accepted, DROP salt, CHANGE email email VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE first_name first_name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE last_name last_name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE middle_name middle_name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
  }
}
