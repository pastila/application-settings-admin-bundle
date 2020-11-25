<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113102754 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(256) NOT NULL, announce LONGTEXT NOT NULL, text LONGTEXT DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_at DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, teaser_image_options JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', is_external TINYINT(1) DEFAULT \'0\' NOT NULL, external_url VARCHAR(256) DEFAULT NULL, slug VARCHAR(256) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE news_news (news_source INT NOT NULL, news_target INT NOT NULL, INDEX IDX_C80E6FDBD323BC07 (news_source), INDEX IDX_C80E6FDBCAC6EC88 (news_target), PRIMARY KEY(news_source, news_target)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE news_news ADD CONSTRAINT FK_C80E6FDBD323BC07 FOREIGN KEY (news_source) REFERENCES s_news (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE news_news ADD CONSTRAINT FK_C80E6FDBCAC6EC88 FOREIGN KEY (news_target) REFERENCES s_news (id) ON DELETE CASCADE');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE news_news DROP FOREIGN KEY FK_C80E6FDBD323BC07');
    $this->addSql('ALTER TABLE news_news DROP FOREIGN KEY FK_C80E6FDBCAC6EC88');
    $this->addSql('DROP TABLE s_news');
    $this->addSql('DROP TABLE news_news');
  }
}
