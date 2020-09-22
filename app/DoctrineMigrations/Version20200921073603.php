<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200921073603 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE s_company_feedbacks (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, region_id INT NOT NULL, company_id INT NOT NULL, valuation INT NOT NULL, head VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E7592224A76ED395 (user_id), INDEX IDX_E759222498260155 (region_id), INDEX IDX_E7592224979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE s_companies (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(512) NOT NULL, type VARCHAR(512) DEFAULT NULL, director VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_2015E64F98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE s_company_feedback_comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AF30E174A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE s_users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE s_regions (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9C2AAC5977153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E7592224A76ED395 FOREIGN KEY (user_id) REFERENCES s_users (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E759222498260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE s_company_feedbacks ADD CONSTRAINT FK_E7592224979B1AD6 FOREIGN KEY (company_id) REFERENCES s_companies (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE s_companies ADD CONSTRAINT FK_2015E64F98260155 FOREIGN KEY (region_id) REFERENCES s_regions (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE s_company_feedback_comments ADD CONSTRAINT FK_AF30E174A76ED395 FOREIGN KEY (user_id) REFERENCES s_users (id) ON DELETE RESTRICT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE s_company_feedbacks');
        $this->addSql('DROP TABLE s_companies');
        $this->addSql('DROP TABLE s_company_feedback_comments');
        $this->addSql('DROP TABLE s_users');
        $this->addSql('DROP TABLE s_regions');
    }
}
