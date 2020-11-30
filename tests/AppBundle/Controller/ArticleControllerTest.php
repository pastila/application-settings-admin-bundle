<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Article\Article;
use Tests\AppBundle\Fixtures\Geo\Region;
use Tests\AppBundle\Fixtures\News\News;
use Symfony\Component\DomCrawler\Crawler;

class ArticleControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Article());
    $this->addFixture(new Region());
    $this->addFixture(new News());
  }

  /*** Проверка "Статьи ":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-85
   **/
  public function testIndex()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/head1');
  }
}