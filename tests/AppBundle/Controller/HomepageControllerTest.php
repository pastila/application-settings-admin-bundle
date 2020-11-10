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


    /*** Проверка "Безбахил в цифрах": **/
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
    // Проверка, что данные вообще нашлись
    $this->assertTrue(count($numbersHtml) > 0);
    // Проверка, что Заголовок совпадает
    $this->assertEquals('Title', $numbersHtml[0]['title']);
    // Проверка, что Описание совпадает
    $this->assertEquals('Description', $numbersHtml[0]['description']);
    // Проверка, что Текст кнопки совпадает
    $this->assertEquals('Go', $numbersHtml[0]['urlText']);
    // Проверка, что Ссылка совпадает
    $this->assertEquals('http://bezbahil.ru/', $numbersHtml[0]['url']);
  }
}