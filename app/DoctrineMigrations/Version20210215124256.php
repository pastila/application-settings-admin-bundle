<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215124256 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    $this->addSql('INSERT INTO s_disease_categories (`name`, position, tree_root_id, parent_id, tree_left, tree_right, tree_level) VALUES (\'root\', 0, 1, NULL, 0, 1, 0)');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    $this->addSql('UPDATE s_disease_categories SET parent_id = NULL');
    $this->addSql('DELETE FROM s_disease_categories WHERE `name` = \'root\'');
  }
}
