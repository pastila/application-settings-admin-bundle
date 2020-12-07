<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207190849 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_companies ADD bitrix_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX bitrix_id_idx ON s_companies (bitrix_id)');
        $this->addSql('ALTER TABLE s_company_branches ADD bitrix_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX bitrix_id_idx ON s_company_branches (bitrix_id)');
        $this->addSql('ALTER TABLE s_regions ADD bitrix_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX bitrix_id_idx ON s_regions (bitrix_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX bitrix_id_idx ON s_companies');
        $this->addSql('ALTER TABLE s_companies DROP bitrix_id');
        $this->addSql('DROP INDEX bitrix_id_idx ON s_company_branches');
        $this->addSql('ALTER TABLE s_company_branches DROP bitrix_id');
        $this->addSql('DROP INDEX bitrix_id_idx ON s_regions');
        $this->addSql('ALTER TABLE s_regions DROP bitrix_id');
    }
}
