<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20201016085742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedback_comment_citations DROP FOREIGN KEY FK_5B347CBDF8697D13');
        $this->addSql('ALTER TABLE s_company_feedback_comment_citations ADD CONSTRAINT FK_5B347CBDF8697D13 FOREIGN KEY (comment_id) REFERENCES s_company_feedback_comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE s_company_feedback_comments DROP FOREIGN KEY FK_AF30E174D249A887');
        $this->addSql('ALTER TABLE s_company_feedback_comments ADD CONSTRAINT FK_AF30E174D249A887 FOREIGN KEY (feedback_id) REFERENCES s_company_feedbacks (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_company_feedback_comment_citations DROP FOREIGN KEY FK_5B347CBDF8697D13');
        $this->addSql('ALTER TABLE s_company_feedback_comment_citations ADD CONSTRAINT FK_5B347CBDF8697D13 FOREIGN KEY (comment_id) REFERENCES s_company_feedback_comments (id)');
        $this->addSql('ALTER TABLE s_company_feedback_comments DROP FOREIGN KEY FK_AF30E174D249A887');
        $this->addSql('ALTER TABLE s_company_feedback_comments ADD CONSTRAINT FK_AF30E174D249A887 FOREIGN KEY (feedback_id) REFERENCES s_company_feedbacks (id)');
    }
}
