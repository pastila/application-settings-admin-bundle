<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Geo\Region;
use Tests\AppBundle\Fixtures\News\News;
use Symfony\Component\DomCrawler\Crawler;

class NewsControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Region());
    $this->addFixture(new News());
  }

  public function testIndex()
  {
    $client = static::createClient();

    /**
     * Проверка, что страница открывается
     */
    $client->request('GET', '/news');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }

  /*** Проверка списка новостей:
   * https://jira.accurateweb.ru/browse/BEZBAHIL-91
   **/
  public function testNewsList()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/news');

    // Извлечение данных со страницы
    $newsHtml = $crawler->filter('.news_item .news_item_content')->each(function (Crawler $node, $i)
    {
      return [
        'title' => trim($node->filter('.news_item_content_title h3')->text()),
        'announce' => trim($node->filter('.news_item_content_text')->text()),
        'url' => $node->filter('.news_item_content_title')->attr('href'),
        'date' => trim($node->filter('.news_item_content_date')->text()),
      ];
    });


    $textPrev = 'Блог, список новостей: ';
    $this->assertTrue(count($newsHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertFalse(count($newsHtml) > 10, $textPrev . 'Проверка, что на одной странице должно быть ограниченное кол-во новостей');
    $this->assertEquals('1', $crawler->filter('.text.block_pagen b')->text(), $textPrev . 'Проверка, что открыта 1-ая страница');
    $this->assertEquals('Заголовок', $newsHtml[0]['title'], $textPrev . 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Анонс статьи', $newsHtml[0]['announce'], $textPrev . 'Проверка, что Анонс совпадает');
    $this->assertEquals('/news/zagolovok', $newsHtml[0]['url'], $textPrev . 'Проверка, что url совпадает');
    $this->assertEquals('02 января, 2020', $newsHtml[0]['date'], $textPrev . 'Проверка, что Дата совпадает');
    $this->assertEquals('Заголовок0', $newsHtml[1]['title'], $textPrev . 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Анонс статьи0', $newsHtml[1]['announce'], $textPrev . 'Проверка, что Анонс совпадает');
    $this->assertEquals('/news/zagolovok0', $newsHtml[1]['url'], $textPrev . 'Проверка, что url совпадает');
    $this->assertEquals('01 января, 2020', $newsHtml[1]['date'], $textPrev . 'Проверка, что Дата совпадает');
  }

  /*** Проверка одной новости:
   * https://jira.accurateweb.ru/browse/BEZBAHIL-91
   **/
  public function testNewsShow()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/news/zagolovok');

    $textPrev = 'Блог, чтение одной новости: ';
    $this->assertEquals('Заголовок', trim($crawler->filter('.page-title')->text()), $textPrev . 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Основное содержание', trim($crawler->filter('.content-news p')->text()), $textPrev . 'Проверка, что Содержание совпадает');
    $this->assertEquals('02 января, 2020', trim($crawler->filter('.content-news .news-date-time')->text()), $textPrev . 'Проверка, что Дата совпадает');
  }
}