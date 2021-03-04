<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303035521 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_company_branches ADD code BIGINT DEFAULT NULL, ADD boss_full_name_dative VARCHAR(255) DEFAULT NULL');
  }

  public function postUp(Schema $schema)
  {
    $sql = 'UPDATE s_company_branches scbo
              JOIN
              (
                SELECT scb.id, scb.kpp, MAX(biep.IBLOCK_ELEMENT_ID) as IBLOCK_ELEMENT_ID
                FROM s_company_branches scb
                JOIN s_regions sr ON sr.id = scb.region_id
                JOIN
                (
                  SELECT biep.IBLOCK_ELEMENT_ID as IBLOCK_ELEMENT_ID, biep.VALUE as KPP, be.IBLOCK_SECTION_ID as IBLOCK_SECTION_ID, be.ACTIVE as ACTIVE
                  FROM b_iblock_element_property biep
                  JOIN b_iblock_element be ON be.ID = biep.IBLOCK_ELEMENT_ID
                  WHERE biep.IBLOCK_PROPERTY_ID = 112
                ) biep ON biep.KPP = scb.kpp AND sr.bitrix_id = IBLOCK_SECTION_ID AND biep.ACTIVE = IF(scb.published, "Y", "N")
                GROUP BY scb.ID
              ) scb ON scbo.id = scb.id
              JOIN b_iblock_element_property biepCODE ON biepCODE.IBLOCK_PROPERTY_ID = 148 AND biepCODE.IBLOCK_ELEMENT_ID = scb.IBLOCK_ELEMENT_ID
              JOIN b_iblock_element_property biepFN ON biepFN.IBLOCK_PROPERTY_ID = 92 AND biepFN.IBLOCK_ELEMENT_ID = scb.IBLOCK_ELEMENT_ID
            SET scbo.code = biepCODE.VALUE, scbo.boss_full_name_dative = biepFN.VALUE;';
    $this->connection->exec($sql);
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE s_company_branches DROP code, DROP boss_full_name_dative');
  }
}
