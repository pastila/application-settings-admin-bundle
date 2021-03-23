<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211050457 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $sql = 'UPDATE s_users su
            SET su.login = su.email
            WHERE su.login = "Admin";';
    $this->addSql($sql);

    $sql = 'UPDATE s_users su
            LEFT JOIN b_user bu ON bu.LOGIN = su.login
            LEFT JOIN b_uts_user uu ON uu.VALUE_ID = bu.ID
            LEFT JOIN b_iblock_section sr ON sr.ID = uu.UF_REGION
            LEFT JOIN s_regions srg ON (srg.name LIKE sr.SEARCHABLE_CONTENT)
            LEFT JOIN b_iblock_element ec ON ec.ID = uu.UF_INSURANCE_COMPANY
            LEFT JOIN b_iblock_element_property cp_kpp ON cp_kpp.IBLOCK_ELEMENT_ID = ec.ID AND cp_kpp.IBLOCK_PROPERTY_ID = 112
            LEFT JOIN s_company_branches scb ON scb.kpp = cp_kpp.VALUE AND scb.region_id = srg.id
            SET
                su.username = su.login,
                su.username_canonical = su.login,
                su.email = su.login,
                su.email_canonical = su.login,
                su.enabled = true,
                su.terms_and_conditions_accepted = true,
                su.confirmation_token = UUID(),
                su.branch_id = scb.id,
                su.insurance_policy_number = uu.UF_INSURANCE_POLICY,
                su.roles = "a:0:{}",
                su.phone = bu.PERSONAL_PHONE,
                su.birth_date = bu.PERSONAL_BIRTHDAY,
                su.password = "";';
    $this->addSql($sql);

    // Для отзывов, которые создали незарегистированные пользователи,
    // Удалить связь с этими пользователями, так как они будут удалены далее в файле
    // И перенести его фио в author_name
    $sql = 'UPDATE s_company_feedbacks f
            JOIN s_users u ON u.id = f.user_id AND u.login IS NULL
            SET
                f.author_name = TRIM(CONCAT(u.last_name, " ", u.first_name, " ", u.middle_name)),
                f.user_id = null;';
    $this->addSql($sql);

    // Удаление пользователей, у которых нет login
    // Т.к. при импорте отзывов из битрикса автоматически создавались пользователи, которые не были зарегистрированы
    $sql = 'DELETE FROM s_users WHERE login IS NULL;';
    $this->addSql($sql);
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
  }
}
