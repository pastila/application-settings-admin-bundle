<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503135330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_oms_charge_complaints ADD disease_id INT DEFAULT NULL, DROP disease, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE s_oms_charge_complaints ADD CONSTRAINT FK_43959F72D8355341 FOREIGN KEY (disease_id) REFERENCES s_diseases (id)');
        $this->addSql('CREATE INDEX IDX_43959F72D8355341 ON s_oms_charge_complaints (disease_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_oms_charge_complaints DROP FOREIGN KEY FK_43959F72D8355341');
        $this->addSql('DROP INDEX IDX_43959F72D8355341 ON s_oms_charge_complaints');
        $this->addSql('ALTER TABLE s_oms_charge_complaints ADD disease VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, DROP disease_id, CHANGE id id VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
