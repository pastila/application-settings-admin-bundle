<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528082112 extends AbstractMigration
{
  public function up (Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_users ADD main_patient_id INT DEFAULT NULL');
    $this->addSql('ALTER TABLE s_users ADD CONSTRAINT FK_6F95FD1E5371E61A FOREIGN KEY (main_patient_id) REFERENCES patients (id) ON DELETE RESTRICT');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_6F95FD1E5371E61A ON s_users (main_patient_id)');
  }

  public function down (Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_users DROP FOREIGN KEY FK_6F95FD1E5371E61A');
    $this->addSql('DROP INDEX UNIQ_6F95FD1E5371E61A ON s_users');
    $this->addSql('ALTER TABLE s_users DROP main_patient_id');
  }
}
