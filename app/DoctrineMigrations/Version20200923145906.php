<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200923145906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE s_company_feedback_comment_citations (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, text LONGTEXT NOT NULL, representative INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5B347CBDA76ED395 (user_id), INDEX IDX_5B347CBDF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE s_company_feedback_comment_citations ADD CONSTRAINT FK_5B347CBDA76ED395 FOREIGN KEY (user_id) REFERENCES s_users (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE s_company_feedback_comment_citations ADD CONSTRAINT FK_5B347CBDF8697D13 FOREIGN KEY (comment_id) REFERENCES s_company_feedback_comments (id) ON DELETE RESTRICT');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2015E64F989D9B62 ON s_companies (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE s_company_feedback_comment_citations');
        $this->addSql('DROP INDEX UNIQ_2015E64F989D9B62 ON s_companies');
    }
}
