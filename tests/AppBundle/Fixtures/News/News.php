<?php


namespace Tests\AppBundle\Fixtures\News;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class News extends Fixture
{
  /**
   * @param ObjectManager $manager
   */
  public function load(ObjectManager $manager)
  {
    $news1 = new \AppBundle\Entity\Common\News();
    $news1->setTitle('Заголовок1');
    $news1->setAnnounce('Анонс статьи1');
    $news1->setText('Основное содержание1');
    $news1->setIsPublished(true);
    $news1->setCreatedAt(new \DateTime('2020-01-02'));
    $news1->setUpdatedAt(new \DateTime('2020-01-02'));
    $news1->setPublishedAt(new \DateTime('2020-01-02'));
    $manager->persist($news1);

    $news2 = new \AppBundle\Entity\Common\News();
    $news2->setTitle('Заголовок2');
    $news2->setAnnounce('Анонс статьи2');
    $news2->setText('Основное содержание2');
    $news2->setIsPublished(true);
    $news2->setCreatedAt(new \DateTime('2020-01-01'));
    $news2->setUpdatedAt(new \DateTime('2020-01-01'));
    $news2->setPublishedAt(new \DateTime('2020-01-01'));
    $manager->persist($news2);

    $manager->flush();

    $this->addReference('news-simple1', $news1);
    $this->addReference('news-simple2', $news1);
  }
}