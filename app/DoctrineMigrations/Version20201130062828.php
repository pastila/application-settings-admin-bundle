<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130062828 extends AbstractMigration
{
  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('CREATE TABLE s_articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(256) NOT NULL, announce LONGTEXT NOT NULL, text LONGTEXT DEFAULT NULL, is_published TINYINT(1) NOT NULL, published_at DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, teaser_image_options JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', is_external TINYINT(1) DEFAULT \'0\' NOT NULL, external_url VARCHAR(256) DEFAULT NULL, slug VARCHAR(256) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE article_article (article_source INT NOT NULL, article_target INT NOT NULL, INDEX IDX_EFE84AD1354DE8F3 (article_source), INDEX IDX_EFE84AD12CA8B87C (article_target), PRIMARY KEY(article_source, article_target)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1354DE8F3 FOREIGN KEY (article_source) REFERENCES s_articles (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD12CA8B87C FOREIGN KEY (article_target) REFERENCES s_articles (id) ON DELETE CASCADE');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1354DE8F3');
    $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD12CA8B87C');
    $this->addSql('DROP TABLE s_articles');
    $this->addSql('DROP TABLE article_article');
  }
}
