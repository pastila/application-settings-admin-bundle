<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215115445 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_disease_categories (id INT AUTO_INCREMENT NOT NULL, tree_root_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, tree_left INT NOT NULL, tree_level INT NOT NULL, tree_right INT NOT NULL, position INT NOT NULL, INDEX IDX_9F6E9E64B381CA9B (tree_root_id), INDEX IDX_9F6E9E64727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE s_diseases (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C15F016412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE s_disease_categories ADD CONSTRAINT FK_9F6E9E64B381CA9B FOREIGN KEY (tree_root_id) REFERENCES s_disease_categories (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE s_disease_categories ADD CONSTRAINT FK_9F6E9E64727ACA70 FOREIGN KEY (parent_id) REFERENCES s_disease_categories (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE s_diseases ADD CONSTRAINT FK_C15F016412469DE2 FOREIGN KEY (category_id) REFERENCES s_disease_categories (id) ON DELETE SET NULL');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_disease_categories DROP FOREIGN KEY FK_9F6E9E64B381CA9B');
    $this->addSql('ALTER TABLE s_disease_categories DROP FOREIGN KEY FK_9F6E9E64727ACA70');
    $this->addSql('ALTER TABLE s_diseases DROP FOREIGN KEY FK_C15F016412469DE2');
    $this->addSql('DROP TABLE s_disease_categories');
    $this->addSql('DROP TABLE s_diseases');
  }
}
