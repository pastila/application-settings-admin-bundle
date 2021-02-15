<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126081850 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_company_branches ADD published TINYINT(1) DEFAULT \'1\' NOT NULL');
    $this->addSql('UPDATE s_company_branches SET published=(status=1)');
    $this->addSql('ALTER TABLE s_company_branches DROP status');
    $this->addSql('ALTER TABLE s_companies ADD published TINYINT(1) DEFAULT \'1\' NOT NULL');
    $this->addSql('UPDATE s_companies SET published=(status=1)');
    $this->addSql('ALTER TABLE s_companies DROP status');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_companies ADD status INT NOT NULL, DROP published');
    $this->addSql('ALTER TABLE s_company_branches ADD status INT NOT NULL, DROP published');
  }
}
