<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20201020155932 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedbacks DROP FOREIGN KEY FK_E759222498260155');
        $this->addSql('DROP INDEX IDX_E759222498260155 ON s_company_feedbacks');
        $this->addSql('ALTER TABLE s_company_feedbacks DROP region_id, CHANGE bitrix_id bitrix_id INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

         $this->addSql('ALTER TABLE s_company_feedbacks ADD region_id INT DEFAULT NULL, CHANGE bitrix_id bitrix_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E759222498260155 FOREIGN KEY (region_id) REFERENCES s_regions (id)');
        $this->addSql('CREATE INDEX IDX_E759222498260155 ON s_company_feedbacks (region_id)');
    }
}
