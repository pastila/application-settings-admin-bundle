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
    $news = new \AppBundle\Entity\Common\News();
    $news->setTitle('Заголовок');
    $news->setAnnounce('Анонс статьи');
    $news->setText('Основное содержание');
    $news->setIsPublished(true);
    $news->setCreatedAt(new \DateTime('2020-01-02'));
    $news->setUpdatedAt(new \DateTime('2020-01-02'));
    $news->setPublishedAt(new \DateTime('2020-01-02'));
    $manager->persist($news);

    for ($i = 0; $i < 10; $i++)
    {
      $news = new \AppBundle\Entity\Common\News();
      $news->setTitle('Заголовок' . $i);
      $news->setAnnounce('Анонс статьи' . $i);
      $news->setText('Основное содержание' . $i);
      $news->setIsPublished(true);
      $news->setCreatedAt(new \DateTime('2020-01-01'));
      $news->setUpdatedAt(new \DateTime('2020-01-01'));
      $news->setPublishedAt(new \DateTime('2020-01-01'));
      $manager->persist($news);
    }

    $manager->flush();
  }
}