<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212051840 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_representatives DROP FOREIGN KEY FK_93322DC6DCD6CC49');
    $this->addSql('ALTER TABLE s_representatives ADD CONSTRAINT FK_93322DC6DCD6CC49 FOREIGN KEY (branch_id) REFERENCES s_company_branches (id) ON DELETE CASCADE');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_representatives DROP FOREIGN KEY FK_93322DC6DCD6CC49');
    $this->addSql('ALTER TABLE s_representatives ADD CONSTRAINT FK_93322DC6DCD6CC49 FOREIGN KEY (branch_id) REFERENCES s_company_branches (id)');
  }
}
