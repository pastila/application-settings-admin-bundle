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
  }

  /*** Проверка "Статьи ":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-85
   **/
  public function testIndex()
  {
    $client = static::createClient();

    $crawler = $client->request('GET', '/head1');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что статья head1 открывается');
    $this->assertEquals('head1', trim($crawler->filter('title')->text()), 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Основное содержание1', trim($crawler->filter('.white_block')->text()), 'Проверка, что Текст совпадает');

    $crawler = $client->request('GET', '/head2');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница head2 открывается');
    $this->assertEquals('head2', trim($crawler->filter('title')->text()), 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Основное содержание2', trim($crawler->filter('.white_block')->text()), 'Проверка, что Текст совпадает');
  }
}