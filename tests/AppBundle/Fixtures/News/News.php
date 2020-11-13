<?php


namespace Tests\AppBundle\Fixtures\News;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class News extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $news = new \AppBundle\Entity\Common\News();
    $news->setTitle('Заголовок');
    $news->setAnnounce('Анонс статьи');
    $news->setIsPublished(true);
    $manager->persist($news);
    $manager->flush();

    $this->addReference('news-simple', $news);
  }
}