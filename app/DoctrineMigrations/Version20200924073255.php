<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200924073255 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_users ADD branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE s_users ADD CONSTRAINT FK_6F95FD1EDCD6CC49 FOREIGN KEY (branch_id) REFERENCES s_company_branches (id) ON DELETE RESTRICT');
        $this->addSql('CREATE INDEX IDX_6F95FD1EDCD6CC49 ON s_users (branch_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_users DROP FOREIGN KEY FK_6F95FD1EDCD6CC49');
        $this->addSql('DROP INDEX IDX_6F95FD1EDCD6CC49 ON s_users');
        $this->addSql('ALTER TABLE s_users DROP branch_id');
    }
}