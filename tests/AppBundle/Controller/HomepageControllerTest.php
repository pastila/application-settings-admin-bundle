<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Number\Number;
use Symfony\Component\DomCrawler\Crawler;

class HomepageControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Number());
  }

  public function testIndex()
  {
    $client = static::createClient();

    /**
     * Проверка, что страница открывается
     */
    $crawler = $client->request('GET', '/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }

  /*** Проверка "Безбахил в цифрах":
  https://jira.accurateweb.ru/browse/BEZBAHIL-90
   **/
  public function testNumber()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $numbersHtml = $crawler->filter('.b-info__item')->each(function (Crawler $node, $i)
    {
      return [
        'title' => $node->filter('.b-info__item-number')->text(),
        'description' => $node->filter('.b-info__item-text')->text(),
        'urlText' => trim($node->filter('.b-info__item-more a')->text()),
        'url' => $node->filter('.b-info__item-more a')->attr('href'),
      ];
    });
    $this->assertTrue(count($numbersHtml) > 1, 'Проверка, что данные вообще нашлись');

    // Проверка, что запись, с позицией 1 отобразиться первой:
    $this->assertEquals('999', $numbersHtml[0]['title'], 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Description', $numbersHtml[0]['description'], 'Проверка, что Описание совпадает');
    $this->assertEquals('Go', $numbersHtml[0]['urlText'], 'Проверка, что Текст кнопки совпадает');
    $this->assertEquals('http://bezbahil.ru/', $numbersHtml[0]['url'], 'Проверка, что Ссылка совпадает');

    $this->assertEquals('100 500', $numbersHtml[1]['title'], 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Example', $numbersHtml[1]['description'], 'Проверка, что Описание совпадает');
    $this->assertEquals('Go', $numbersHtml[1]['urlText'], 'Проверка, что Текст кнопки совпадает');
    $this->assertEquals('http://example.ru/', $numbersHtml[1]['url'], 'Проверка, что Ссылка совпадает');
  }
}