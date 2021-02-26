<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226110817 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_diseases DROP FOREIGN KEY FK_C15F016412469DE2');
        $this->addSql('ALTER TABLE s_diseases ADD code VARCHAR(8) NOT NULL');
        $this->addSql('ALTER TABLE s_diseases ADD CONSTRAINT FK_C15F016412469DE2 FOREIGN KEY (category_id) REFERENCES s_disease_categories (id) ON DELETE RESTRICT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_diseases DROP FOREIGN KEY FK_C15F016412469DE2');
        $this->addSql('ALTER TABLE s_diseases DROP code');
        $this->addSql('ALTER TABLE s_diseases ADD CONSTRAINT FK_C15F016412469DE2 FOREIGN KEY (category_id) REFERENCES s_disease_categories (id) ON DELETE SET NULL');
    }
}
