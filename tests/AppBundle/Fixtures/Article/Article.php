<?php


namespace Tests\AppBundle\Fixtures\Article;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Article extends Fixture
{
  /**
   * @param ObjectManager $manager
   */
  public function load(ObjectManager $manager)
  {
    $article1 = new \AppBundle\Entity\Common\Article();
    $article1->setTitle('head1');
    $article1->setSlug('head1');
    $article1->setAnnounce('Анонс статьи1');
    $article1->setText('Основное содержание1');
    $article1->setIsPublished(true);
    $article1->setCreatedAt(new \DateTime('2020-01-02'));
    $article1->setUpdatedAt(new \DateTime('2020-01-02'));
    $article1->setPublishedAt(new \DateTime('2020-01-02'));
    $manager->persist($article1);

    $article2 = new \AppBundle\Entity\Common\Article();
    $article2->setTitle('head2');
    $article1->setSlug('head2');
    $article2->setAnnounce('Анонс статьи2');
    $article2->setText('Основное содержание2');
    $article2->setIsPublished(true);
    $article2->setCreatedAt(new \DateTime('2020-01-01'));
    $article2->setUpdatedAt(new \DateTime('2020-01-01'));
    $article2->setPublishedAt(new \DateTime('2020-01-01'));
    $manager->persist($article2);

    $manager->flush();

    $this->addReference('article-simple1', $article1);
    $this->addReference('article-simple2', $article2);
  }
}