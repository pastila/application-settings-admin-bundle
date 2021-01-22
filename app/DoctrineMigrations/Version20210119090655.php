<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119090655 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs

    $this->addSql('UPDATE settings SET name = \'administrator_email\' WHERE name = \'default_email\'');
    $this->addSql('UPDATE settings SET name = \'contact_email\' WHERE name = \'main_email\'');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs

    $this->addSql('UPDATE settings SET name = \'default_email\' WHERE name = \'administrator_email\'');
    $this->addSql('UPDATE settings SET name = \'main_email\' WHERE name = \'contact_email\'');
  }
}
